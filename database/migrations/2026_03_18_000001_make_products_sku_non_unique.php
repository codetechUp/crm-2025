<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_sku_unique');
        });

        DB::table('attributes')
            ->where('entity_type', 'products')
            ->where('code', 'sku')
            ->update([
                'is_unique' => 0,
                'name'      => 'Référence',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unique('sku');
        });

        DB::table('attributes')
            ->where('entity_type', 'products')
            ->where('code', 'sku')
            ->update([
                'is_unique' => 1,
                'name'      => 'SKU',
            ]);
    }
};
