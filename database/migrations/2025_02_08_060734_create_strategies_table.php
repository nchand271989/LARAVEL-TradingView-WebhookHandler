<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('strategies', function (Blueprint $table) {
            $table->unsignedBigInteger('stratid')->primary();           /** Store Snowflake ID as an integer for unique strategy identification */ 

            $table->string('name');                                     /** Name of the strategy, a string that holds the strategy's name */ 
            $table->text('pineScript');                                 /** Pine Script code that defines the strategy's logic */ 
            
            $table->unsignedBigInteger('createdBy')->nullable();        /** Foreign key for the user who created this strategy */ 
            $table->unsignedBigInteger('lastUpdatedBy')->nullable();    /** Foreign key for the user who last updated this strategy */ 
            
            $table->timestamps();                                       /** Timestamps for created_at and updated_at */ 
            $table                                                      /** Enum for the status of the strategy, can be 'Active' or 'Inactive', default is 'Active' */
                ->enum('status', ['Active', 'Inactive'])
                ->default('Active');

            /** Define foreign key constraints */ 
            $table                                                      /** Add foreign key constraint for 'createdBy', references 'id' in the 'users' table, cascading on delete */
                ->foreign('createdBy')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table                                                      /** Add foreign key constraint for 'lastUpdatedBy', references 'id' in the 'users' table, cascading on delete */ 
                ->foreign('lastUpdatedBy')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('strategies');
    }
};
