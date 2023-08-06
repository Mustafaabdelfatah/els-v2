<?php


namespace App\Repositories\Eloquent;

use App\Models\Organization;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserOrganization;
use App\Repositories\Contracts\IUser;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\MailNotification;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;

class UsersRepository extends BaseRepository implements IUser
{
    public function model()
    {
        return User::class;
    }

    public function getById($id)
    {
        return $this->getModelClass()->with('roles')->where('id', $id)->first();
    }


    public function createWithRoles(array $data, array $roles, $password = '')
    {
        DB::beginTransaction();
        try {
            $user = User::withTrashed()->firstOrNew([
                'email' => $data['email'],
            ]);

            $user->name = $data['name'];
            $user->domain = 'default';
            $user->guid = $data['guid'] ?? null;
            $user->password = Hash::make($password);
            $user->avatar = $data['avatar'] ?? null;
            $user->phone = $data['phone'] ?? null;
            $user->type = $data['type'] ?? null;
            $user->organization_id = $data['organization_id'] ?? null;
            $user->user_reset_password = $data['user_reset_password'] ?? null;
            $user->active = true;

            $user->deleted_at = null;
            $user->save();
            $user->refresh();


            $user->syncRoles((new RolesRepository())->getByNameIn($roles));
            if (in_array('Admin',$roles)) {
                UserOrganization::create([
                    'user_id' => $user->id,
                    'organization_id' => $user->organization_id
                ]);
            }
            $message = 'Welcome ' . $user->name . ' Your Password is :' . $password;
            $page = Setting::where('key', 'MAIL_NOTIFY_NEW_USER')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[password]]',
                ],
                [
                    $user->name,
                    $user->email,
                    $password,
                ],
                $page
            );

            $ldapUser = \LdapRecord\Models\OpenLDAP\User::whereStartsWith('mail', $data['email'])
                ->orWhereStartsWith('name', $user->name)->get();
            if ($ldapUser->isEmpty())
                $user->notify(new MailNotification($page, ['user' => $user, 'password' => $password], $message));

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateWithRoles($id, array $data, array $roles)
    {
        try {
            DB::beginTransaction();

            $user = parent::update($id, $data);
            $user->syncRoles((new RolesRepository())->getByNameIn($roles));

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProfile($id, $password, array $data)
    {
        try {
            $current_password = auth()->user()->password;
            if (Hash::check($password, $current_password)) {
                DB::beginTransaction();
                $user = parent::update($id, $data);
                DB::commit();
                return $user;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }// end updateProfile

    public function legalUsers(array $requesterIds, array $permissions)
    {
        try {

            $findPermissions = array();
            $findUsers = array();
            $users = array();
            $organizationIds = array();

            $assignRequestSetting = Setting::where('key', 'ASSIGN_REQUEST')->first('value')->value;


            foreach ($permissions as $permission) {
                $findPermissions[] = Permission::where('name', 'like', '%' . $permission . '%')->pluck('name');
            }

            foreach ($requesterIds as $requesterId)
            {
                $organizationIds[] = User::where('id',$requesterId)->pluck('organization_id');
            }

            $query = DB::table('users')
                ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftJoin('role_has_permissions', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
                ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->leftJoin('forms', 'forms.user_id', '=', 'users.id')
                ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
                ->whereNotIn('users.id', $requesterIds)
                ->where('users.id','!=',1)
                ->where('model_has_roles.role_id','=',3)
                ->select('users.id', 'users.name')
                ->groupBy('users.id');

            if ($assignRequestSetting === '1') {
                foreach ($findPermissions as $permission) {
                    $findUsers[] = $query->whereIn('users.organization_id', $organizationIds)
                                        ->whereIn('permissions.name', $permission)
                                        ->get();
                }

            } elseif ($assignRequestSetting === '2') {
                $findUsers[] = $query->get();
            }

            $findUsers = array_map(function ($item) {
                return \Arr::pluck($item, 'id');
            }, $findUsers);

            $first = $findUsers[0];
            for ($i = 0; $i < count($findUsers); $i++) {
                $result = array_intersect($first, $findUsers[$i]);
                $first = $result;
            }

            $users = array_map(function ($item) {
                return DB::table('users')->select('users.id', 'users.name')->where('id', $item)->first();
            }, $result);

            return $users;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updatePassword($id, array $data, $password = '')
    {
        try {
            DB::beginTransaction();
            $user = parent::update($id, $data);
            $message = 'Your Password is :' . $password;
            $page = Setting::where('key', 'MAIL_NOTIFY_CHANGE_PASSWORD')->first('value')->value;

            $page = str_replace(
                [
                    '[[name]]',
                    '[[email]]',
                    '[[password]]',
                ],
                [
                    $user->name,
                    $user->email,
                    $password,
                ],
                $page
            );
            $user->notify(new MailNotification($page, ['user' => $user], $message));
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAdminOrganizations($user)
    {
//        $organizations = [];
//        if(in_array('Root', $user->getRoleNames()->toArray()))
//        {
            $organizations = Organization::all();
//        }
//        if(in_array('Admin', $user->getRoleNames()->toArray()))
//        {
//            foreach (userOrganization() as $organizationId)
//            {
//                $organization = Organization::find($organizationId);
//                $organizations[] = $organization;
//            }
//        }
        return $organizations;
    }

}
