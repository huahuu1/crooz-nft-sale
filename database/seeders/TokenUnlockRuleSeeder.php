<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokenUnlockRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('token_unlock_rules')->insert([
            [
                'id' => 1,
                'rule_code' => 1,
                'period' => 120,
                'unit' => 'DAY',
                'unlock_percentages' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'rule_code' => 2,
                'period' => 60,
                'unit' => 'DAY',
                'unlock_percentages' => 25,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'rule_code' => 2,
                'period' => 120,
                'unit' => 'DAY',
                'unlock_percentages' => 75,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'rule_code' => 3,
                'period' => 0,
                'unit' => 'DAY',
                'unlock_percentages' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'rule_code' => 3,
                'period' => 30,
                'unit' => 'DAY',
                'unlock_percentages' => 25,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'rule_code' => 3,
                'period' => 60,
                'unit' => 'DAY',
                'unlock_percentages' => 65,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'rule_code' => 4,
                'period' => 1,
                'unit' => 'MONTH',
                'unlock_percentages' => 25,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'rule_code' => 5,
                'period' => 1,
                'unit' => 'YEAR',
                'unlock_percentages' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
