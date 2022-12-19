<?php

namespace Database\Seeders;

use App\Models\XenoClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class XenoClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = collect([
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier1_auction1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier2_auction1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier3_auction1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier4_auction1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier5_auction1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier6_auction1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_tier7_auction1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier9_weapon.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier9_weapon.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier10_weapon.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/tier10_charm.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_breaker_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_psychic_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_samurai_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_guardian_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/charm_001_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/charm_002_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/charm_003_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_001_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_002_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_003_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/skin_004_tgs_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_breaker_tw1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_guardian_tw1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_samurai_tw1_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_ninja_tw2_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_mayweather_tw3_en.png',
            'https://d1aevkh4jc7ik5.cloudfront.net/nft/chara_gxepartner2211_en.png',
        ]);
        // Xeno Class
        $xenoClasses = [
            [
                'id' => 1,
                'class' => 'BREAKER',
                'standard_img' => $images->random(),
                'special_img' => $images->random(),
                'premium_img' => $images->random(),
                'legandary_img' => $images->random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'class' => 'GUARDIAN',
                'standard_img' => $images->random(),
                'special_img' => $images->random(),
                'premium_img' => $images->random(),
                'legandary_img' => $images->random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'class' => 'SAMURAI',
                'standard_img' => $images->random(),
                'special_img' => $images->random(),
                'premium_img' => $images->random(),
                'legandary_img' => $images->random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'class' => 'PSYCHIC',
                'standard_img' => $images->random(),
                'special_img' => $images->random(),
                'premium_img' => $images->random(),
                'legandary_img' => $images->random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'class' => 'NINJA',
                'standard_img' => $images->random(),
                'special_img' => $images->random(),
                'premium_img' => $images->random(),
                'legandary_img' => $images->random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'class' => 'GRAPPLER',
                'standard_img' => $images->random(),
                'special_img' => $images->random(),
                'premium_img' => $images->random(),
                'legandary_img' => $images->random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'class' => 'RANDOM',
                'standard_img' => $images->random(),
                'special_img' => $images->random(),
                'premium_img' => $images->random(),
                'legandary_img' => $images->random(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        XenoClass::insert($xenoClasses);
    }
}
