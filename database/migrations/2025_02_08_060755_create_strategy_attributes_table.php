<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('strategy_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('strategy_id');      /** Foreign key to reference the strategy ID from the 'strategies' table */
            $table->string('attribute_name');               /** Name of the strategy attribute */
            $table->string('attribute_value');              /** Value of the strategy attribute */
            $table->timestamps();                           /** Timestamps for created_at and updated_at */ 

            $table                                          /** Define foreign key constraints to link the strategy attributes to strategies */
                ->foreign('strategy_id')
                ->references('stratid')
                ->on('strategies')
                ->onDelete('cascade');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('strategy_attributes');
    }
};
