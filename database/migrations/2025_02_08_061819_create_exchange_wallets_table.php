<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('exchange_wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('wltid')->primary();
            $table->unsignedBigInteger('exchange_id');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('exchange_id')->references('exid')->on('exchanges');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('exchange_wallets');
    }
};
