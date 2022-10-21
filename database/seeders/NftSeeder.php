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
                'nft_id' => 1,
                'nft_type' => 1,
                'name' => 'Genesis Xeno',
                'image_url' => 'https://media-be.chewy.com/wp-content/uploads/2021/05/27140116/Pug_FeaturedImage.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 2,
                'nft_type' => 2,
                'name' => 'Genesis Xeno',
                'image_url' => 'https://kimipet.vn/wp-content/uploads/2021/05/co-nen-cao-long-mau-cho-poodle.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 3,
                'nft_type' => 3,
                'name' => 'Genesis Xeno',
                'image_url' => 'https://image-us.eva.vn/upload/3-2022/images/2022-08-19/image10-1660873964-106-width770height546.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 4,
                'nft_type' => 4,
                'name' => 'Genesis Xeno',
                'image_url' => 'https://sieupet.com/sites/default/files/phoc_soc9_0.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 5,
                'nft_type' => 5,
                'name' => 'Genesis Xeno',
                'image_url' => 'https://thuvienthucung.com/wp-content/uploads/2021/09/Cho-Chihuahua-Dac-Diem-Noi-Bat-Cach-Nuoi-Cham-Soc.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
