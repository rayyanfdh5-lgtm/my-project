<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();
        $categories = Category::all();

        // Skip seeding if no suppliers or categories exist
        if ($suppliers->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $items = [
            [
                'nama' => 'Laptop ASUS ROG',
                'keterangan' => 'Laptop gaming ASUS ROG dengan spesifikasi tinggi',
                'harga' => 15000000,
                'stok_total' => 10,
                'stok_reguler' => 5,
                'stok_peminjaman' => 5,
                'type' => 'peminjaman',
                'gambar' => null,
                'supplier_id' => $suppliers->first()->id,
                'category_id' => $categories->first()->id,
            ],
            [
                'nama' => 'Mouse Gaming Logitech',
                'keterangan' => 'Mouse gaming dengan sensor presisi tinggi',
                'harga' => 850000,
                'stok_total' => 25,
                'stok_reguler' => 15,
                'stok_peminjaman' => 10,
                'type' => 'peminjaman',
                'gambar' => null,
                'supplier_id' => $suppliers->skip(1)->first()->id ?? $suppliers->first()->id,
                'category_id' => $categories->first()->id,
            ],
            [
                'nama' => 'Keyboard Mechanical',
                'keterangan' => 'Keyboard mechanical dengan switch blue',
                'harga' => 1200000,
                'stok_total' => 15,
                'stok_reguler' => 8,
                'stok_peminjaman' => 7,
                'type' => 'peminjaman',
                'gambar' => null,
                'supplier_id' => $suppliers->first()->id,
                'category_id' => $categories->first()->id,
            ],
            [
                'nama' => 'Proyektor Epson',
                'keterangan' => 'Proyektor untuk presentasi dan meeting',
                'harga' => 8500000,
                'stok_total' => 8,
                'stok_reguler' => 3,
                'stok_peminjaman' => 5,
                'type' => 'peminjaman',
                'gambar' => null,
                'supplier_id' => $suppliers->skip(2)->first()->id ?? $suppliers->first()->id,
                'category_id' => $categories->first()->id,
            ],
            [
                'nama' => 'Kamera DSLR Canon',
                'keterangan' => 'Kamera DSLR Canon EOS untuk fotografi profesional',
                'harga' => 12500000,
                'stok_total' => 6,
                'stok_reguler' => 2,
                'stok_peminjaman' => 4,
                'type' => 'peminjaman',
                'gambar' => null,
                'supplier_id' => $suppliers->skip(3)->first()->id ?? $suppliers->first()->id,
                'category_id' => $categories->first()->id,
            ],
            [
                'nama' => 'Microphone Audio Technica',
                'keterangan' => 'Microphone condenser untuk recording dan streaming',
                'harga' => 2500000,
                'stok_total' => 12,
                'stok_reguler' => 6,
                'stok_peminjaman' => 6,
                'type' => 'peminjaman',
                'gambar' => null,
                'supplier_id' => $suppliers->skip(4)->first()->id ?? $suppliers->first()->id,
                'category_id' => $categories->first()->id,
            ],
            // Items untuk stok (bisa dijual/digunakan habis)
            [
                'nama' => 'Kertas A4 80gsm',
                'keterangan' => 'Kertas A4 putih 80gsm untuk keperluan kantor',
                'harga' => 45000,
                'stok_total' => 100,
                'stok_reguler' => 100,
                'stok_peminjaman' => 0,
                'type' => 'stok',
                'gambar' => null,
                'supplier_id' => $suppliers->first()->id,
                'category_id' => $categories->skip(1)->first()->id ?? $categories->first()->id,
            ],
            [
                'nama' => 'Tinta Printer HP',
                'keterangan' => 'Tinta printer HP original untuk printer inkjet',
                'harga' => 350000,
                'stok_total' => 50,
                'stok_reguler' => 50,
                'stok_peminjaman' => 0,
                'type' => 'stok',
                'gambar' => null,
                'supplier_id' => $suppliers->skip(1)->first()->id ?? $suppliers->first()->id,
                'category_id' => $categories->skip(1)->first()->id ?? $categories->first()->id,
            ],
            [
                'nama' => 'Pulpen Pilot',
                'keterangan' => 'Pulpen pilot hitam untuk keperluan tulis menulis',
                'harga' => 5000,
                'stok_total' => 200,
                'stok_reguler' => 200,
                'stok_peminjaman' => 0,
                'type' => 'stok',
                'gambar' => null,
                'supplier_id' => $suppliers->first()->id,
                'category_id' => $categories->skip(1)->first()->id ?? $categories->first()->id,
            ],
            [
                'nama' => 'Baterai AA Alkaline',
                'keterangan' => 'Baterai AA alkaline untuk perangkat elektronik',
                'harga' => 15000,
                'stok_total' => 80,
                'stok_reguler' => 80,
                'stok_peminjaman' => 0,
                'type' => 'stok',
                'gambar' => null,
                'supplier_id' => $suppliers->skip(2)->first()->id ?? $suppliers->first()->id,
                'category_id' => $categories->skip(1)->first()->id ?? $categories->first()->id,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
