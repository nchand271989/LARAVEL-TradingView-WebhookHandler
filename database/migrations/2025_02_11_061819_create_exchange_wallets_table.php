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
        Schema::create('exchange_wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('wltid')->primary();
            $table->unsignedBigInteger('exid');
            $table->bigInteger('scnid')->nullable()->unique(); // Allow only one wallet per scenario
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('scnid')->references('scnid')->on('scenarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_wallets');
    }
};
