<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('ledger', function (Blueprint $table) {
            $table->id();                                           /** The primary key of the table, an auto-incrementing ID for each ledger entry. */ 
            $table->unsignedBigInteger('wallet_id');                /** Foreign key to the 'wallets' table, linking each ledger entry to a specific wallet. */ 
            $table->decimal('amount', 16, 8);                       /** The amount of the transaction, stored as a decimal with up to 16 digits and 8 decimal places. */
            $table                                                  /** Enum column for the transaction type, which can be either 'Credit' or 'Debit') */ 
                ->enum('transaction_type', ['Credit', 'Debit']);
            $table->text('description')->nullable();                /** A description of the transaction, stored as text. This field is nullable. */
            $table->timestamp('transaction_time')->useCurrent();    /** The timestamp when the transaction occurred. It defaults to the current timestamp. */ 
            $table->timestamps();                                   /** Timestamps for created_at and updated_at */ 

            $table
                ->foreign('wallet_id')
                ->references('wltid')
                ->on('wallets')
                ->onDelete('cascade');                              /** Foreign Key constraint for wallet_id referencing 'wallets' */
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('ledger');
    }
};
