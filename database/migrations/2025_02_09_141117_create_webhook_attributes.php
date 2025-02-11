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
            $table->unsignedBigInteger('webhid'); // Store Snowflake ID as an integer
            $table->string('attribute_name');
            $table->string('attribute_value');
            $table->timestamps();
            $table->foreign('webhid')->references('webhid')->on('webhooks')->onDelete('cascade');
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
