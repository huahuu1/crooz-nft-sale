<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->makeRoleAdmin();
    }

    protected function makeRoleAdmin()
    {
        if (!$this->adminExists()) {
            DB::table('roles')->insert([
                'id' => 1,
                'name' => 'admin',
                'description' => 'Admin role',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    protected function adminExists()
    {
        return DB::table('roles')->where('id', 1)->exists();
    }
}
