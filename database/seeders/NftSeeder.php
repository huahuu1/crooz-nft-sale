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
                'name' => 'GENESIS SPECIAL',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier1_auction1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 2,
                'nft_type' => 1,
                'name' => 'GENESIS FIRST',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier2_auction1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 3,
                'nft_type' => 1,
                'name' => 'GENESIS SECOND',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier3_auction1_en.png',
                'created_at' => date('-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 4,
                'nft_type' => 1,
                'name' => 'GENESIS THIRD',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier4_auction1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 5,
                'nft_type' => 1,
                'name' => 'GENESIS FOURTH',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier5_auction1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 6,
                'nft_type' => 1,
                'name' => 'GENESIS FIFTH',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier6_auction1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [

                'nft_id' => 7,
                'nft_type' => 1,
                'name' => 'XENO',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier7_auction1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 8,
                'nft_type' => 3,
                'name' => 'RARE WEAPON',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier9_weapon.png',
                'created_at' => date('-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 9,
                'nft_type' => 4,
                'name' => 'RARE CHARM',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier9_weapon.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 10,
                'nft_type' => 3,
                'name' => 'RARE WEAPON',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier10_weapon.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 11,
                'nft_type' => 4,
                'name' => 'RARE CHARM',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier10_charm.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 12,
                'nft_type' => 1,
                'name' => 'BREAKER',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_breaker_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 13,
                'nft_type' => 1,
                'name' => 'PSYCHIC',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_psychic_tgs_en.png',
                'created_at' => date('-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 14,
                'nft_type' => 1,
                'name' => 'SAMURAI',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_samurai_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 15,
                'nft_type' => 1,
                'name' => 'GUARDIAN',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_guardian_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 16,
                'nft_type' => 4,
                'name' => 'CHARM',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/charm_001_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 17,
                'nft_type' => 4,
                'name' => 'CHARM',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/charm_002_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 18,
                'nft_type' => 4,
                'name' => 'CHARM',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/charm_003_tgs_en.png',
                'created_at' => date('-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 19,
                'nft_type' => 2,
                'name' => 'SKIN',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_001_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 20,
                'nft_type' => 2,
                'name' => 'SKIN',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_002_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 21,
                'nft_type' => 2,
                'name' => 'SKIN',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_003_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 22,
                'nft_type' => 2,
                'name' => 'SKIN',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_004_tgs_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 23,
                'nft_type' => 1,
                'name' => 'BREAKER',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_breaker_tw1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 24,
                'nft_type' => 1,
                'name' => 'GUARDIAN',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_guardian_tw1_en.png',
                'created_at' => date('-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 25,
                'nft_type' => 1,
                'name' => 'SAMURAI',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_samurai_tw1_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 26,
                'nft_type' => 1,
                'name' => 'NINJA',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_ninja_tw2_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 27,
                'nft_type' => 1,
                'name' => 'MAYWEATHER',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_mayweather_tw3_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nft_id' => 28,
                'nft_type' => 1,
                'name' => 'XENO',
                'image_url' => 'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_gxepartner2211_en.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
