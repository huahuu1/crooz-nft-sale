<?php

namespace Database\Seeders;

use App\Models\Nft;
use App\Models\NftAuctionWeaponGachaId;
use Illuminate\Container\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator;

class NftAuctionWeaponGachaIdSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $weapons = [];
        for ($i = 0; $i < 20; $i++) {
            $weapons[$i] = [
                'nft_id' => $this->faker->randomElement(Nft::pluck('nft_id')->toArray()),
                'weapon_gacha_id' =>  $this->faker->numberBetween(10, 60),
            ];
        }
        NftAuctionWeaponGachaId::insert($weapons);
    }
}