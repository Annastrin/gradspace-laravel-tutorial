<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 3; $i++) {
            $title = $faker->word();
            DB::table('products')->insert([
                'title' => $title,
                'price' => $faker->randomFloat(2, 1, 10000),
                'description' => $faker->text(15),
                'image' => 'public/images/' . $faker->image('public/storage/images', 360, 360, null, false, false, $title, false)
            ]);
        }
    }
}
