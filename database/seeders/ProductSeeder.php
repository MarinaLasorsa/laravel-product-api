<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $category_ids = Category::all()->pluck('id')->all();

        for ($i = 0; $i < 100; $i++){
            $new_product = new Product();

            $new_product->name = implode(' ', $faker->words(3));
            $new_product->description = $faker->sentence();
            $new_product->price = $faker->randomFloat(2, 5, 500);

            $new_product->save();

            $random_category_ids = $faker->randomElements($category_ids);

            $new_product->categories()->attach($random_category_ids);
        }
    }
}
