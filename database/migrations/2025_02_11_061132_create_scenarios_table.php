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
            $table->bigInteger('scnid')->primary(); // Use bigInteger for Snowflake ID
            $table->string('name')->unique();
            $table->decimal('ratio', 5, 2)->nullable();
            $table->boolean('auto_exit')->default(false);
            $table->boolean('stop_loss')->default(false);
            $table->boolean('target_profit')->default(false);
            $table->timestamps();
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
