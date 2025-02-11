<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->unsignedBigInteger('exid')->primary();                                                  // Exchange Id
            $table->string('name', 255)->unique();                                                          // Exchange name
            $table->unsignedBigInteger('createdBy');                                                        // Foreign key to store userId of user created this exchange
            $table->unsignedBigInteger('lastUpdatedBy');                                                    // Foreign key to store userId of user last updated this exchange
            $table->timestamps();                                                                           // Created_at and Updated_at timestamps
            $table->enum('status', ['Active', 'Inactive'])->default('Active');                              // Store status of exchanges whether it is Active or Inactive

            /** Define foreign key constraints */ 
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('lastUpdatedBy')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');                                                                 // Drop the table if the migration is rolled back
    }
};
