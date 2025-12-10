<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pagrindiniai produktai
        $transportas = Product::firstOrCreate(
            ['title' => 'TRANSPORTAS'],
            ['title' => 'TRANSPORTAS', 'parent_id' => null]
        );

        $turtas = Product::firstOrCreate(
            ['title' => 'TURTAS'],
            ['title' => 'TURTAS', 'parent_id' => null]
        );

        $kita = Product::firstOrCreate(
            ['title' => 'KITA'],
            ['title' => 'KITA', 'parent_id' => null]
        );

        // Subproduktai TRANSPORTAS
        Product::firstOrCreate(
            ['title' => 'KASKO', 'parent_id' => $transportas->id],
            ['title' => 'KASKO', 'parent_id' => $transportas->id]
        );

        Product::firstOrCreate(
            ['title' => 'MTPL', 'parent_id' => $transportas->id],
            ['title' => 'MTPL', 'parent_id' => $transportas->id]
        );

        Product::firstOrCreate(
            ['title' => 'KITA', 'parent_id' => $transportas->id],
            ['title' => 'KITA', 'parent_id' => $transportas->id]
        );
    }
}
