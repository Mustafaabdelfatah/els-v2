<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserOrganization;
use App\Repositories\Eloquent\OrganizationsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class OrganizationController extends Controller
{
    private $repo;

    public function __construct(OrganizationsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $orders = [];
        $query = [];
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
            $search = ($request->input('search')['value'] != "All"
                and $request->input('search')['value'] != "Not trashed"
                and $request->input('search')['value'] != "Trashed") ?
                $request->input('search') : [];
            $filter = ($request->input('search')['value'] == "All"
                or $request->input('search')['value'] == "Trashed") ?
                $request->input('search')['value'] : null;
        } else {
            $search = [];
            $filter = '';
        }
        $data = $this->repo->ajaxPaginate($length, $start, $query, $filter, $search, $columns, $orders);

        return response()->json([
            'data' => OrganizationResource::collection($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int)$data->total(),
            'recordsTotal' => (int)$data->total(),
        ]);
    }

    public function getOrganization($id)
    {
        $data = $this->repo->getById($id);
        try {
            return response()->json(self::getByIdCollection($data));
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public static function getByIdCollection($data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'description' => $data->description,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
            'users' => UserOrganization::with('user')
                ->where('organization_id', $data->id)->get()->pluck('user')->toArray()
        ];
    }

    public function store(OrganizationRequest $request)
    {
        try {
            $ins = $this->repo->createOrganization([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            if ($ins) {
                if ($request->users) {
                    foreach ($request->users as $user) {
                        $roleUser = User::find($user['id']);
                        UserOrganization::create([
                            'organization_id' => $ins->id,
                            'user_id' => $user['id'],
                        ]);
                        $roleUser->assignRole(Role::where('name', 'Admin')->first());
                    }
                }
            }
            return $ins;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
            }

            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function update(OrganizationRequest $request)
    {
        try {
            if ($this->repo->getModelClass()->findOrFail($request->id))
                $res = $this->repo->updateOrganization($request->id, [
                    'name' => $request->name,
                    'description' => $request->description,
                ]);
            if ($res) {
                /* remove role from users how assigned to this org*/
                $userOldRoles = UserOrganization::where('organization_id', $request->id)->pluck('user_id');
//                return $userOldRoles;
                foreach ($userOldRoles as $userOldRole) {
                    $adminOrganizations = UserOrganization::where('user_id', $userOldRole)->pluck('organization_id');
                    if($adminOrganizations->count() < 2)
                    {
                        $user = User::find($userOldRole);
                        $user->removeRole(Role::where('name', 'Admin')->first());
                    }
                }
                UserOrganization::where('organization_id', $request->id)->delete();
                foreach ($request->users as $user) {
                    $roleUser = User::find($user['id']);

                    UserOrganization::create([
                        'organization_id' => $res->id,
                        'user_id' => $user['id'],
                    ]);
                    $roleUser->assignRole(Role::where('name', 'Admin')->first());
                }
            }
            return $res;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return response()->json(['errors' => ['name' => ['This name already exists']]], 422);
            }

            return response()->json(['message' => 'Unknown error', $e], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            return $this->repo->bulkDelete($request->ids ?? [], false);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            return $this->repo->bulkRestore($request->ids ?? [], false);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function getAllOrganization()
    {
        $data = $this->repo->organizationByAdmin();
        return response()->json([
            'data' => OrganizationResource::collection($data),
        ]);

    }
}
