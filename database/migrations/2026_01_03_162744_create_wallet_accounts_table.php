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
        Schema::create('wallet_accounts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
    $table->string('currency_code', 10);
    $table->enum('type', ['fiat', 'crypto']);
    $table->decimal('balance', 20, 8)->default(0);
    $table->boolean('is_configured')->default(false);
    $table->string('status')->default('active');
    $table->timestamps();

    $table->unique(['wallet_id', 'currency_code']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_accounts');
    }
};
