<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'nama' => 'PT. Teknologi Maju',
                'company_name' => 'PT. Teknologi Maju',
                'contact_person' => 'Ahmad Santoso',
                'email' => 'ahmad@teknologimaju.com',
                'phone' => '+62-21-555-0123',
                'address' => 'Jl. Teknologi No. 123, Jakarta',
                'status' => 'active',
            ],
            [
                'nama' => 'CV. Elektronik Global',
                'company_name' => 'CV. Elektronik Global',
                'contact_person' => 'Sari Wijaya',
                'email' => 'sari@elektronikglobal.com',
                'phone' => '+62-21-555-0124',
                'address' => 'Jl. Elektronik No. 456, Surabaya',
                'status' => 'active',
            ],
            [
                'nama' => 'UD. Suku Cadang Berkualitas',
                'company_name' => 'UD. Suku Cadang Berkualitas',
                'contact_person' => 'Budi Hartono',
                'email' => 'budi@sukucadang.com',
                'phone' => '+62-21-555-0125',
                'address' => 'Jl. Industri No. 789, Bandung',
                'status' => 'active',
            ],
            [
                'nama' => 'PT. Pasokan Premium',
                'company_name' => 'PT. Pasokan Premium',
                'contact_person' => 'Lisa Permata',
                'email' => 'lisa@pasokanpremium.com',
                'phone' => '+62-21-555-0126',
                'address' => 'Jl. Pasokan No. 321, Medan',
                'status' => 'active',
            ],
            [
                'nama' => 'CV. Komponen Terpercaya',
                'company_name' => 'CV. Komponen Terpercaya',
                'contact_person' => 'David Susanto',
                'email' => 'david@komponenterpercaya.com',
                'phone' => '+62-21-555-0127',
                'address' => 'Jl. Komponen No. 654, Yogyakarta',
                'status' => 'active',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
