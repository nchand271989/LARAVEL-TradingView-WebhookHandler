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
        Schema::create('strategy_attributes', function (Blueprint $table) {
            $table->id();
            $table->uuid('strgrid');
            $table->string('attribute_name');
            $table->string('attribute_value');
            $table->timestamps();

            $table->foreign('strgrid')->references('strgrid')->on('strategies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategy_attributes');
    }
};
