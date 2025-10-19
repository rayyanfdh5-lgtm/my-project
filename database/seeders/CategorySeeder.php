<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Perangkat elektronik dan gadget',
                'status' => 'active',
            ],
            [
                'name' => 'Alat Tulis Kantor',
                'description' => 'Peralatan tulis dan keperluan kantor',
                'status' => 'active',
            ],
            [
                'name' => 'Buku',
                'description' => 'Buku dan publikasi',
                'status' => 'active',
            ],
            [
                'name' => 'Rumah & Taman',
                'description' => 'Perlengkapan rumah dan taman',
                'status' => 'active',
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Peralatan olahraga dan aksesoris',
                'status' => 'active',
            ],
            [
                'name' => 'Otomotif',
                'description' => 'Suku cadang dan aksesoris mobil',
                'status' => 'active',
            ],
            [
                'name' => 'Kesehatan & Kecantikan',
                'description' => 'Produk kesehatan dan kecantikan',
                'status' => 'active',
            ],
            [
                'name' => 'Mainan & Permainan',
                'description' => 'Mainan dan hiburan',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
