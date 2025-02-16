<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('webhook_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('webhook_id');                                                                  // Foreign key to store exchange id against currency
            $table->unsignedBigInteger('rule_id');                                                                  // Foreign key to store currency id against exchange

            // Define foreign key constraints
            $table->foreign('webhook_id')->references('webhid')->on('webhooks')->onDelete('cascade');
            $table->foreign('rule_id')->references('rid')->on('rules')->onDelete('cascade');
            
            $table->primary(['webhook_id', 'rule_id']);                                                            // Define a composite primary key using both 'exchange_id' and 'currency_id'.
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('webhook_rules');                                                                      // Drop the table if the migration is rolled back
    }
};
