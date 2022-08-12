<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nfts')->insert([
            [
                'id' => 1,
                'serial_no' => '0001',
                'type_id' => 1,
                'nft_id' => '0001',
                'nft_owner_id' => 1,
                'image_url' => 'https://lh3.googleusercontent.com/Hufiq7eGfsfUN-UvzDeBNlDmG3B7VkF147g4VS4KhIien4uY85tNdFAUDUpQGfFWZ_jr7Q77dDARPd51YzyK4PxA-t4uMMx-ZTxoNw=w600',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'serial_no' => '0002',
                'type_id' => 1,
                'nft_id' => '0001',
                'nft_owner_id' => 1,
                'image_url' => 'https://lh3.googleusercontent.com/Hufiq7eGfsfUN-UvzDeBNlDmG3B7VkF147g4VS4KhIien4uY85tNdFAUDUpQGfFWZ_jr7Q77dDARPd51YzyK4PxA-t4uMMx-ZTxoNw=w600',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'serial_no' => '0002',
                'type_id' => 1,
                'nft_id' => '0001',
                'nft_owner_id' => 1,
                'image_url' => 'https://lh3.googleusercontent.com/Hufiq7eGfsfUN-UvzDeBNlDmG3B7VkF147g4VS4KhIien4uY85tNdFAUDUpQGfFWZ_jr7Q77dDARPd51YzyK4PxA-t4uMMx-ZTxoNw=w600',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'serial_no' => '0002',
                'type_id' => 1,
                'nft_id' => '0001',
                'nft_owner_id' => 1,
                'image_url' => 'https://lh3.googleusercontent.com/Hufiq7eGfsfUN-UvzDeBNlDmG3B7VkF147g4VS4KhIien4uY85tNdFAUDUpQGfFWZ_jr7Q77dDARPd51YzyK4PxA-t4uMMx-ZTxoNw=w600',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
