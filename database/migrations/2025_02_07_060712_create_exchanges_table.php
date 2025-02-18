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
            $table->unsignedBigInteger('exid')->primary();              /** Primary key for the exchanges table, using an unsigned big integer to store a unique exchange ID */
            $table->string('name', 255)->unique();                      /** The name of the exchange, stored as a string with a maximum length of 255 characters */
            $table->unsignedBigInteger('createdBy')->nullable();        /** Foreign key to reference the user who created the exchange */
            $table->unsignedBigInteger('lastUpdatedBy')->nullable();    /** Foreign key to reference the user who last updated the exchange */
            $table->timestamps();                                       /** Timestamps for created_at and updated_at */ 
            $table                                                      /** An enum to store the status of the exchange, either 'Active' or 'Inactive', with 'Active' as the default */
                ->enum('status', ['Active', 'Inactive'])
                ->default('Active');

            /** Define foreign key constraints */ 
            $table                                                      /** The 'createdBy' column is a foreign key that references the 'id' column in the 'users' table. */
                ->foreign('createdBy')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); 
            $table                                                      /** The 'lastUpdatedBy' column is a foreign key that references the 'id' column in the 'users' table. */
                ->foreign('lastUpdatedBy')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
