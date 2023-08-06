<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\TemplateStoreRequest;
use App\Http\Requests\TemplateUpdateRequest;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use App\Models\User;
use App\Repositories\Eloquent\TemplatesRepository;
use App\Models\TemplateOrganization;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use Spatie\Permission\Models\Permission;

class TemplatesController extends Controller
{
    protected $repo;

    public function __construct(TemplatesRepository $repo)
    {
        $this->repo = $repo;
        $this->middleware('permission:list-templates|edit-templates|create-templates|delete-templates', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-templates', ['only' => ['store']]);
        $this->middleware('permission:edit-templates', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-templates', ['only' => ['destroy', 'delete_all']]);
    }

    public function index(Request $request)
    {
        $orders = [];
        $query = [];
        $user = Auth::user();
        $templateOrganizations = TemplateOrganization::pluck('template_id');
        if ($request->has('order') && count($request->input('order')))
            foreach ($request->input('order') as $order) {
                $column = isset($request->input("columns")[$order['column']]['data']) ? $request->input("columns")[$order['column']]['data'] : null;
                if ($column)
                    $orders[$column] = $order['dir'];
                else {
                }
            }
        $length = ($request->has('length')) ? $request->input('length') : 10; // meta length
        $start = ($request->has('start')) ? $request->start : 0; // meta start
        $columns = ($request->has('columns')) ? $request->columns : []; // meta start
        if ($request->input('search')) {
            $search = ($request->input('search')['value'] != "All" and $request->input('search')['value'] != "Not trashed" and $request->input('search')['value'] != "Trashed") ? $request->input('search') : [];
            $filter = ($request->input('search')['value'] == "All" or $request->input('search')['value'] == "Trashed") ? $request->input('search')['value'] : null;
        } else {
            $search = [];
            $filter = '';
        }
        $res = $this->repo->ajaxPaginate($length, $start, $query, $filter, $search, $columns, $orders, ["user"],true);
        // $data = $this->repo->ajaxPaginate($request, ['type' => ['=' => 'form']]);

        if (in_array('Admin', $user->getRoleNames()->toArray())) {
            $res->whereHas('organizations',function ($organization){
                $organization->whereIn('organization_id',userOrganization());
            })->orWhereNotIn('id',$templateOrganizations)->get();
        };
        $data = $res->select('templates.*')->paginate($length);
        return response()->json([
            'data' => TemplateResource::collection($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int) $data->total(),
            'recordsTotal' => (int) $data->total(),
        ]);
    }

    public function getAll(Request $request)
    {
        return response()->json($this->repo->all()->pluck('name', 'id'));
    }
    public function all(Request $request)
    {
        return response()->json($this->repo->all());
    }

    public function getUserTemplates()
    {
        $user = Auth::user();
        return response()->json($this->repo->getUserTemplates($user));
    }

    public function get($template)
    {
        $data = $this->repo->find($template);

        return response()->json(new TemplateResource($data));
    }

    public function getUsers($q = null)
    {
        return responseSuccess([
            'data' => User::where('active', true)
                ->where('type', 'user')
                ->where('name', 'like', "%{$q}%")
                ->with([
                    'department' => function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%");
                    },
                ])
                ->get(['id', 'name', 'avatar']),
        ], 'data returned successfully');
    }

    public function store(TemplateStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->repo->createTemplate($request->name,$request->ar_name, auth()->id(), $request->template_id ?? null, $request->icon,$request->organization_id);
            DB::commit();
            if ($data) {
                return response()->json(new TemplateResource($data));
            } else {
                return response()->json('something went wrong', 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
            return response()->json('something went wrong', 500);
        }
    }

    public function updateTemplate(Request $request)
    {
        try {
            if ($this->repo->getModelClass()->findOrFail($request->id))
                $res = $this->repo->updateTemplate($request->id,$request->name,$request->ar_name, $request->icon,$request->organization_id);
            return $res;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
            }
            return response()->json(['message' => 'Unknown error', $e], 500);
        }
    }

    public function update(Template $template, TemplateUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->repo->update($request, $template);
            DB::commit();

            if ($data) {
                return response()->json(new TemplateResource($template->refresh()));
            } else {
                return response()->json('something went wrong', 500);
            }
        } catch (\Exception $e) {
            return $e;
//            return response()->json('something went wrong', 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            foreach ($request->ids as $tempId) {
                $tempName = Template::where('id', $tempId)->pluck('name');
                Permission::where('name', $tempName)->delete();
            };
            return $this->repo->bulkDelete($request->ids ?? [], false);
        } catch (Exception $e) {
            // return [$request->ids,$e->getMessage()];
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }
    public function restore(Request $request)
    {
        try {
            return $this->repo->bulkRestore($request->ids ?? [], false);
        } catch (Exception $e) {
            // return [$request->ids,$e->getMessage()];
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function assign(Request $request) {
        DB::beginTransaction();
        try {
//            return $request->all();
            $data = $this->repo->assignAdmin($request->template_id,$request->selectedAdmins);
            DB::commit();
            if ($data)
                return response()->json(['data' => $data,'message' => 'successfully assigned to user','code' => 200 ],200);
            else
                return response()->json(['data' => [],'message' => 'bad request','code' => 400], 400);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getUserOrganization(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->repo->getUsersOfOrganizations($request->organization_id);
            DB::commit();
            if ($data)
                return response()->json(['data' => $data,'message' => 'successfully get users','code' => 200 ],200);
            else
                return response()->json(['data' => [],'message' => 'bad request','code' => 400], 400);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
