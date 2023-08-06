<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $rootUser = \App\Models\Organization::create([
            'name' => 'NCMS',
            'description' => null,
        ]);
    }
}
