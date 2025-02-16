<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->unsignedBigInteger('curid')->primary();     /** Primary key for the currency table, using an unsigned big integer */ 
            $table->string('name', 100)->unique();              /** Currency name, it is a unique field and limited to 100 characters */
            $table->string('shortcode', 10)->unique();          /** Currency shortcode, unique and limited to 10 characters (e.g., BTC, ETH) */
            $table->unsignedBigInteger('createdBy');            /** Foreign key to store the ID of the user who created this currency entry */ 
            $table->unsignedBigInteger('lastUpdatedBy');        /** Foreign key to store the ID of the user who last updated this currency entry */ 
            $table->timestamps();                               /** Timestamps for created_at and updated_at */ 
            $table                                              /** Enum field to store the status of the currency, can either be 'Active' or 'Inactive', default is 'Active' */
                ->enum('status', ['Active', 'Inactive'])
                ->default('Active');

            /** Define foreign key constraints */ 
            $table                                              /** Reference to the 'users' table for 'createdBy', with cascading delete */
                ->foreign('createdBy')
                ->references('id')->on('users')
                ->onDelete('cascade');           
            $table                                              /** Reference to the 'users' table for 'lastUpdatedBy', with cascading delete */
                ->foreign('lastUpdatedBy')
                ->references('id')->on('users')
                ->onDelete('cascade');       
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
