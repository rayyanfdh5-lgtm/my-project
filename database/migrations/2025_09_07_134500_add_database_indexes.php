<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->index(['tipe', 'created_at']);
            $table->index(['item_id', 'tipe']);
            $table->index('status');
        });

        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'stok_total')) {
                $table->index('stok_total');
            }
            if (Schema::hasColumn('items', 'supplier_id') && Schema::hasColumn('items', 'category_id')) {
                $table->index(['supplier_id', 'category_id']);
            }
            $table->index('nama');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropIndex(['tipe', 'created_at']);
            $table->dropIndex(['item_id', 'tipe']);
            $table->dropIndex(['status']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex(['stok_total']);
            $table->dropIndex(['supplier_id', 'category_id']);
            $table->dropIndex(['nama']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
        });
    }
};
