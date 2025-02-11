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
        Schema::create('ledger', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('wltid');
            $table->decimal('amount', 16, 8);
            $table->enum('transaction_type', ['Credit', 'Debit']);
            $table->text('description')->nullable();
            $table->timestamp('transaction_time')->useCurrent();
            $table->timestamps();

            $table->foreign('wltid')->references('wltid')->on('exchange_wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger');
    }
};
