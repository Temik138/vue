<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::all()->each(function ($product) {
            $product->rating = round(rand(10, 50) / 10, 1); // От 1.0 до 5.0 с шагом 0.1
            $product->save();
        });

        $this->command->info('Product ratings updated!');
    }
}