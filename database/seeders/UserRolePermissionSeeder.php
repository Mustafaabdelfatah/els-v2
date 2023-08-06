<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Templates
            ['group' => 'Templates', 'name' => 'create-templates', 'display_name' => 'Create', 'guard_name' => 'api'],
            ['group' => 'Templates', 'name' => 'edit-templates', 'display_name' => 'Edit', 'guard_name' => 'api'],
            ['group' => 'Templates', 'name' => 'list-templates', 'display_name' => 'List', 'guard_name' => 'api'],
            ['group' => 'Templates', 'name' => 'delete-templates', 'display_name' => 'Delete', 'guard_name' => 'api'],
            //Review
            ['group' => 'review' , 'name' => 'review' , 'display_name' => 'Request Reviews', 'guard_name' => 'api'],
            // Roles
            ['group' => 'Roles', 'name' => 'create-roles', 'display_name' => 'Create', 'guard_name' => 'api'],
            ['group' => 'Roles', 'name' => 'edit-roles', 'display_name' => 'Edit', 'guard_name' => 'api'],
            ['group' => 'Roles', 'name' => 'list-roles', 'display_name' => 'List', 'guard_name' => 'api'],
            ['group' => 'Roles', 'name' => 'delete-roles', 'display_name' => 'Delete', 'guard_name' => 'api'],
            // Users
            ['group' => 'Users', 'name' => 'create-users', 'display_name' => 'Create User', 'guard_name' => 'api'],
            ['group' => 'Users', 'name' => 'list-users', 'display_name' => 'List Users', 'guard_name' => 'api'],
            ['group' => 'Users', 'name' => 'edit-users', 'display_name' => 'Edit Users', 'guard_name' => 'api'],
            ['group' => 'Users', 'name' => 'delete-users', 'display_name' => 'Delete Users', 'guard_name' => 'api'],
            ['group' => 'Users', 'name' => 'restore-users', 'display_name' => 'Restore Users', 'guard_name' => 'api'],
            ['group' => 'Users', 'name' => 'change-password', 'display_name' => 'Change Password', 'guard_name' => 'api'],
            [ 'group' => 'Reports', 'name' => 'request-report', 'display_name' => 'Request Report', 'guard_name' => 'api'],
            [   'group' => 'Legal',
                'name' => 'tracking-Legal advice form request',
                'display_name' => 'tracking-Legal advice form request',
                'guard_name' => 'api'
            ],
            [   'group' => 'Litigation',
                'name' => 'tracking-Litigation and request form',
                'display_name' => 'tracking-Litigation and request form',
                'guard_name' => 'api'
            ],
            [   'group' => 'Contract',
                'name' => 'tracking-Contract review form',
                'display_name' => 'tracking-Contract review form',
                'guard_name' => 'api'
            ],

            /*
                ['group' => '', 'name' => '', 'display_name' => '', 'guard_name' => 'api'],
            */

        ];


        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();
        DB::table('permissions')->delete();
        DB::table('roles')->delete();
        DB::table('users')->delete();

        // default admin users
        $rootUser = User::create([
            'name' => 'ROOT',
            'type' => 'Admin',
            'email' => 'wakeb.tech@gmail.com',
            'organization_id' => null,
             'active' => 1,
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now()
        ]);
        $adminUser = User::create([
            'name' => 'LDS',
            'type' => 'Admin',
            'email' => 'admin@lds.com',
            'organization_id' => 1,
            'active' => 1,
            // 'username' => 'lds',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now()
        ]);
        $employeeUser = User::create([
            'name' => 'Emp LDS',
            'type' => 'Admin',
            'email' => 'emp@lds.com',
            'organization_id' => 1,
            'active' => 1,
            // 'username' => 'emp-lds',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now()
        ]);

        // default roles
        $rootRole = Role::create([
            'name' => "Root",
            'display_name' => 'Root',
            'guard_name' => 'api'
        ]);
        $adminRole = Role::create([
            'name' => "Admin",
            'display_name' => 'Admin',
            'guard_name' => 'api'
        ]);
        $employeeRole = Role::create([
            'name' => "Employee",
            'display_name' => 'Employee',
            'guard_name' => 'api'
        ]);
        DB::table('permissions')->insert($permissions);
        $rootPermissions = Permission::whereNotIn('name',[
            'tracking-Legal advice form request',
            'tracking-Litigation and request form',
            'tracking-Contract review form'
        ])->pluck('name');
        $employeePermissions = Permission::whereIn('name',[
            'tracking-Legal advice form request',
            'tracking-Litigation and request form',
            'tracking-Contract review form'
        ])->whereNotIn('name',['request_report'])->pluck('name');

        $rootUser->assignRole($rootRole);
        $adminUser->assignRole($adminRole);
        $employeeUser->assignRole($employeeRole);


        // add default permissions

        $rootRole->syncPermissions($rootPermissions);
        $adminRole->syncPermissions($rootPermissions);
        $employeeRole->syncPermissions($employeePermissions);
    }
}
