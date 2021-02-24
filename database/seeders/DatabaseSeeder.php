<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $brands = Brand::factory()->count(5)->create();
        // Generate Products using random brand_id from the above array
        Product::factory()->count(20)->state(new Sequence(
            fn () => ['brand_id' => $brands->random()],
        ))->create();

    }
}
