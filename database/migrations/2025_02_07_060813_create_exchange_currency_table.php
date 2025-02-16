<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('exchange_currency', function (Blueprint $table) {
            $table->unsignedBigInteger('exchange_id');                                                                  // Foreign key to store exchange id against currency
            $table->unsignedBigInteger('currency_id');                                                                  // Foreign key to store currency id against exchange

            // Define foreign key constraints
            $table->foreign('exchange_id')->references('exid')->on('exchanges')->onDelete('cascade');
            $table->foreign('currency_id')->references('curid')->on('currencies')->onDelete('cascade');
            
            $table->primary(['exchange_id', 'currency_id']);                                                            // Define a composite primary key using both 'exchange_id' and 'currency_id'.
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('exchange_currency');                                                                      // Drop the table if the migration is rolled back
    }
};
