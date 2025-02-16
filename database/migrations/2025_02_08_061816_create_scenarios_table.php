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
        Schema::create('scenarios', function (Blueprint $table) {
            $table->unsignedBigInteger('scnrid')->primary();
            $table->string('name');
            $table->unsignedBigInteger('webhook_id');
            $table->timestamp('transaction_time')->useCurrent();
            $table->timestamps();

            $table->foreign('webhook_id')->references('webhid')->on('webhooks')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenarios');
    }
};
