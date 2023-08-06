<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RoleStoreRequest;
use App\Http\Requests\Auth\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Repositories\Eloquent\RolesRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private $repo;

    /**
     * Create a new controller instance.
     *
     * @param RolesRepository $repo
     */
    public function __construct(RolesRepository $repo)
    {
        $this->repo = $repo;
        $this->middleware('permission:list-roles|edit-roles|delete-roles|create-roles', ['only' => ['index']]);
        $this->middleware('permission:create-roles', ['only' => ['store']]);
        $this->middleware('permission:edit-roles', ['only' => ['update']]);
        $this->middleware('permission:delete-roles', ['only' => ['destroy']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $orders = [];
        $query = [];
        $user = Auth::user();

        if ($request->has('order') && count($request->input('order')))
            foreach ($request->input('order') as $order) {
                $column = isset($request->input("columns")[$order['column']]['data']) ? $request->input("columns")[$order['column']]['data'] : null;
                if ($column)
                    $orders[$column] = $order['dir'];
                else {

                }
            }
        $length = ($request->has('length')) ? $request->input('length') : 10; // meta length
        $start = ($request->has('start')) ? $request->start: 0; // meta start
        $columns = ($request->has('columns')) ? $request->columns: []; // meta start
        $search = ($request->input('search')) ? $request->input('search') : [];
        $filter = $request->input('filter');
        $res = $this->repo->ajaxPaginate($length,$start, $query, $filter ,$search ,$columns ,$orders,["permissions","organizations"], true);
        if (in_array('Root', $user->getRoleNames()->toArray()))
        {
            //
        }
        elseif (in_array('Employee', $user->getRoleNames()->toArray()) || in_array('Admin', $user->getRoleNames()->toArray())) {
            $res->whereHas('organizations',function ($organization){
                $organization->whereIn('organization_id',userOrganization());
            });
        }
        $res->where('name','!=','Root');
        $data = $res->select('roles.*')->paginate($length);
        return response()->json([
            'data' => RoleResource::collection($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int) $data->total(),
            'recordsTotal' => (int) $data->total(),
        ]);
    }

    public function getAllRoles()
    {
        return response()->json($this->repo->all());
    }

    public function getRole(Request $request ,$id)
    {
        $data = $this->repo->getById($id);
        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleStoreRequest $request
     * @return JsonResponse
     */
    public function store(RoleStoreRequest $request)
    {
        // abort_plan_if(!Gate::check('fx_create_roles'));
        // abort_plan_if(!Gate::check('create_roles'));
        $organizations = array();
        if ($request->organizations)
            $organizations = $request->organizations;
        try {
            return $this->repo->createWithPermissions([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'guard_name' => "api",
            ], $request->permissions, $organizations);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
            }

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * update a permission.
     *
     * @param RoleUpdateRequest $request
     * @return JsonResponse
     */
    public function update(RoleUpdateRequest $request)
    {
        // abort_plan_if(!Gate::check('fx_create_roles'));
        $organizations = array();
        if ($request->organizations)
            $organizations = $request->organizations;
        try {
            // if ($this->repo->getModelClass()->where('name', 'like', primaryID() . ".%")->findOrFail($request->id))
            if ($this->repo->getModelClass()->findOrFail($request->id))
                return $this->repo->updateWithPermissions($request->id, [
                    'name' => $request->name,
                    'display_name' => $request->display_name,
                ], $request->permissions, $organizations);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
            }

            return response()->json(['message' => 'Unknown error', $e], 500);
        }
    }

    /**
     * Delete more than one roles.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $list_ids = [1,2,3];
            foreach($request->ids as $id) {
                if(in_array($id, $list_ids))
                    return response()->json(['message' => 'false']);
                else
                    return $this->repo->bulkDelete($request->ids ?? [], true);
            }
        } catch (Exception $e) {
            // return [$request->ids,$e->getMessage()];
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }
}
