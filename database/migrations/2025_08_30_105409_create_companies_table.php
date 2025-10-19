<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama instansi
            $table->string('email')->nullable(); // Email instansi
            $table->string('phone')->nullable(); // Nomor telepon
            $table->text('address')->nullable(); // Alamat lengkap
            $table->string('website')->nullable(); // Website
            $table->string('logo')->nullable(); // Logo instansi
            $table->string('tax_number')->nullable(); // Nomor NPWP
            $table->string('business_number')->nullable(); // Nomor SIUP/TDP
            $table->text('description')->nullable(); // Deskripsi instansi
            $table->string('owner_name')->nullable(); // Nama pemilik/direktur
            $table->string('owner_phone')->nullable(); // Nomor telepon pemilik
            $table->string('owner_email')->nullable(); // Email pemilik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
