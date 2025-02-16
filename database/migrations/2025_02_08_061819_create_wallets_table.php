<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('wltid')->primary();
            $table->unsignedBigInteger('webhook_id');
            $table->unsignedBigInteger('rule_id');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('webhook_id')->references('webhid')->on('webhooks');
            $table->foreign('rule_id')->references('rid')->on('rules');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
