<?php

namespace Database\Seeders;

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Ielādē visu produktu katalogu (34 produkti, nosaukumi neatkārtojas).
     */
    public function run(): void
    {
        foreach (ProductFactory::catalog() as $item) {
            Product::factory()->fromCatalog($item)->create();
        }
    }
}
