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
            $table->unsignedBigInteger('exchange_id');          /** Foreign key to store the exchange id associated with the currency */
            $table->unsignedBigInteger('currency_id');          /** Foreign key to store the currency id associated with the exchange */ 

            /** Define foreign key constraints */ 
            $table                                              /** If the referenced exchange is deleted, the corresponding row in this table will be deleted as well (onDelete('cascade')) */
                ->foreign('exchange_id')
                ->references('exid')
                ->on('exchanges')
                ->onDelete('cascade');
            $table                                              /** If the referenced currency is deleted, the corresponding row in this table will be deleted as well (onDelete('cascade')) */
                ->foreign('currency_id')
                ->references('curid')
                ->on('currencies')
                ->onDelete('cascade');
            
            $table->primary(['exchange_id', 'currency_id']);    /** Define a composite primary key using both 'exchange_id' and 'currency_id' */
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('exchange_currency');
    }
};
