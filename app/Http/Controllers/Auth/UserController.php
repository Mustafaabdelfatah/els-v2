<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserStoreRequest;
use App\Http\Requests\Auth\UserUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\AssignRequest;
use App\Models\Form;
use App\Models\User;
use App\Repositories\Eloquent\UsersRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Models\Attributes\Guid;
use Carbon\Carbon;

class UserController extends Controller
{
    private $repo;

    /**
     * Create a new controller instance.
     *
     * @param UsersRepository $repo
     */
    public function __construct(UsersRepository $repo)
    {
        $this->repo = $repo;
        // $this->repo->setPrimaryID(primaryID());
        $this->middleware('permission:list-users|edit-users|delete-users|create-users|change-password', ['only' => ['index', 'store', 'changeUserPassword']]);
        $this->middleware('permission:create-users', ['only' => ['store']]);
        $this->middleware('permission:edit-users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy', 'delete_all']]);
        $this->middleware('permission:change-password', ['only' => ['changeUserPassword']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $orders = [];
        $wheres = [];
        $user = \Auth::user();
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
            $deleteFilter = ["All", "Not trashed", "Trashed"];
            $search = (!in_array($request->input('search')['value'], $deleteFilter)) ?
                $request->input('search') : [];
            $filter = (in_array($request->input('search')['value'], $deleteFilter)) ?
                $request->input('search')['value'] : null;
        } else {
            $search = [];
            $filter = '';
        }
        $query = $this->repo->ajaxPaginate($length, $start, $wheres, $filter, $search, $columns, $orders, ['permissions'], true);

            if (in_array('Root', $user->getRoleNames()->toArray()))
            {
                //
            }
            elseif (in_array('Employee', $user->getRoleNames()->toArray()) || in_array('Admin', $user->getRoleNames()->toArray())) {
                $query->whereIn('organization_id',userOrganization());
            }
            $query->where('id', '!=', auth()->user()->id);
            $query->where('name', '!=', 'ROOT');
        $data = $query->select('users.*')->paginate($length);
        return response()->json([
            'data' => UserResource::collection($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int)$data->total(),
            'recordsTotal' => (int)$data->total(),
        ]);
    }

    public function getUser($id)
    {
        $data = $this->repo->getById($id);
        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function ldapUsers($q = '')
    {
        try {
            $users = [];
            foreach (\LdapRecord\Models\OpenLDAP\User::whereStartsWith('cn', $q)
                         ->get(['cn', 'mail', 'entryuuid']) as $ldapUser) {

                $users[] = [
                    'name' => $ldapUser->cn[0] ?? '',
                    'email' => $ldapUser->mail[0] ?? '',
                    'guid' => (string)new Guid($ldapUser->entryuuid[0]),

                ];
            }

            return response()->json($users);

            $users = [];
            foreach (\LdapRecord\Models\ActiveDirectory\User::whereStartsWith('cn', $q)
                         ->orWhereStartsWith('name', $q)->limit(20)
                         ->get(['cn', 'name', 'mail', 'objectguid']) as $ldapUser) {

                $users[] = [
                    'name' => $ldapUser->name[0] ?? $ldapUser->cn[0] ?? '',
                    'email' => $ldapUser->mail[0] ?? '',
                    'guid' => (string)new Guid($ldapUser->objectguid[0] ?? ''),

                ];
            }

            return response()->json($users);
        } catch (Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     * @return Builder|Exception|\Illuminate\Database\Eloquent\Builder|Model|User
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $password = Str::random(15);
            return $this->repo->createWithRoles([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($password),
                'type' => $request->type,
                'organization_id' => (!empty($request->organization_id)) ? $request->organization_id : 1,
                'department_id' => $request->department_id,
                'guid' => $request->guid,
                'user_reset_password' => auth()->user()->id,
                'domain' => 'default',
                'active' => true,],
                $request->roles,
                $password,
            );
        } catch (Exception $e) {
            return $e;
//            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    /**
     * update a permission.
     *
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request)
    {
        // abort_plan_if(!Gate::check('fx_create_users'));
        try {
            return $this->repo->updateWithRoles($request->id, [
                'name' => $request->name,
                // 'username' => $request->username,
                'email' => $request->email,
                'type' => $request->type,
                'phone' => $request->phone,
                'organization_id' => (!empty($request->organization_id)) ? $request->organization_id : 1,
                'department_id' => $request->department_id,
            ], $request->roles);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }


    /**
     * Delete more than one permission.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        // abort_plan_if(!Gate::check('fx_create_users'));
        DB::beginTransaction();
        try {
            foreach ($request->ids as $id)
            {
                $assignedUser = AssignRequest::where('user_id',$id)->first();
                if($assignedUser)
                {
                    $form = Form::find($assignedUser->form_id);
                    $form->update(['status','pending']);
                    $assignedUser->delete();
                }
                else{
                    $form = Form::where('user_id',$id)->first();
                    if($form){
                        $form->delete();
                    }
                }
            }
            DB::commit();
            return $this->repo->bulkDelete($request->ids ?? [], false);
        } catch (Exception $e) {
             return [$request->ids,$e->getMessage()];
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

    public function getLegalUsers(Request $request)
    {
//        try{
        $data = $this->repo->legalUsers($request->requesterIds ?? [], $request->permissions ?? []);
        return response()->json($data);
//        } catch (Exception $e) {
//            return response()->json(['message' => 'Unknown error'], 500);
//        }
    }

    public function changeUserPassword(Request $request)
    {
        $this->validate($request, [
            'password' => ['required', 'min:6', 'confirmed'],
        ]);
        return $this->repo->updatePassword($request->id, [
            'password' => bcrypt($request->password),
            'user_reset_password' => auth()->user()->id
        ],
            $request->password);
    }

    public function getAdminOrganizations()
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $data = $this->repo->getAdminOrganizations($user);
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
