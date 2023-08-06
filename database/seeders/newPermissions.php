<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class newPermissions extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [   'group' => 'Legal',
                'name' => 'assign-Legal advice form request',
                'display_name' => 'assign-Legal advice form request',
                'guard_name' => 'api'
            ],
            [   'group' => 'Litigation',
                'name' => 'assign-Litigation and request form',
                'display_name' => 'assign-Litigation and request form',
                'guard_name' => 'api'
            ],
            [   'group' => 'Contract',
                'name' => 'assign-Contract review form',
                'display_name' => 'assign-Contract review form',
                'guard_name' => 'api'
            ],
        ];
        DB::table('permissions')->insert($permissions);
    }
}
