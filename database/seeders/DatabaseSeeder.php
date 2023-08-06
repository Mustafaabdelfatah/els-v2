<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(UserRolePermissionSeeder::class);
        $this->call(TemplateSqlFileSeeder::class);
        $this->call(newPermissions::class);
    }
}
