<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->makeAdminUser();
    }

    protected function makeAdminUser()
    {
        if (!$this->adminExists()) {
            DB::table('users')->insert([
                'id' => 1,
                'role_id' => 1,
                'mail' => 'admin@gmail.com',
                'status' => 1,
                'password' => Hash::make('123456'),
                'is_verified' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    protected function adminExists()
    {
        return DB::table('users')->where('role_id', 1)->exists();
    }
}
