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
        Schema::create('webhook_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('webhook_id'); // Store Snowflake ID as an integer
            $table->string('attribute_name');
            $table->string('attribute_value');
            $table->timestamps();
            $table->foreign('webhook_id')->references('webhid')->on('webhooks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_attributes');
    }
};
