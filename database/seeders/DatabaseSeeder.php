<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    $this->call([
        ProductSeeder::class,
    ]);
    $this->call([
        AdminUserSeeder::class,
    ]);
    $this->call(ProductRatingSeeder::class); 
}
}
