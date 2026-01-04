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
        Schema::create('wallet_account_configs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('wallet_account_id')->constrained()->cascadeOnDelete();

    // Fiat
    $table->string('iban')->nullable();
    $table->string('account_number')->nullable();
    $table->string('bank_name')->nullable();

    // Crypto
    $table->string('wallet_address')->nullable();
    $table->string('blockchain_network')->nullable();

    $table->json('metadata')->nullable();
    $table->timestamp('validated_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_account_configs');
    }
};
