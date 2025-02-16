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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->unsignedBigInteger('webhid')->primary(); // Store Snowflake ID as an integer
            $table->string('name');
            $table->unsignedBigInteger('strategy_id');
            $table->unsignedBigInteger('exchange_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('lastUpdatedBy');
            $table->timestamps();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            $table->foreign('strategy_id')->references('stratid')->on('strategies');
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade'); // Foreign key reference
            $table->foreign('lastUpdatedBy')->references('id')->on('users')->onDelete('cascade'); // Foreign key reference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
