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
        Schema::create('trades', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('webhook_id');
            $table->unsignedBigInteger('strategy_id');
            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('wallet_id');
            $table->string('positionType');
            $table->decimal('quantity', 16, 3);
            $table->enum('timeframe', ['1m', '3m', '5m', '10m', '15m', '30m', 'H', 'D', 'M']);
            $table->decimal('openingPrice', 16, 8);
            $table->timestamp('openingTime');
            $table->decimal('closingPrice', 16, 8)->nullable();
            $table->timestamp('closingTime')->nullable();
            $table->text('comments')->nullable();
            $table->enum('status', ['Active', 'Closed'])->default('Active');
            $table->timestamps();

            $table->foreign('webhook_id')->references('webhid')->on('webhooks');
            $table->foreign('strategy_id')->references('stratid')->on('strategies');
            $table->foreign('rule_id')->references('rid')->on('rules');
            $table->foreign('wallet_id')->references('wltid')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
