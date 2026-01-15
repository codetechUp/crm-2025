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
        Schema::create('admin_alert_reads', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned(); // Using integer for admin user id as seen in other tables/code
            $table->integer('order_id')->unsigned();
            $table->string('alert_type')->nullable(); // 'delayed' or 'risk'
            $table->timestamps();

            // Index for faster lookups
            $table->index(['user_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_alert_reads');
    }
};
