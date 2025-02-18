<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('wltid')->primary();         /** The primary key for the table. Stores a unique identifier for each wallet. */
            $table->unsignedBigInteger('webhook_id')->nullable();   /** Foreign key referencing the 'webhid' in the 'webhooks' table, establishing a relationship between the wallet and a webhook. */
            $table->unsignedBigInteger('rule_id')->nullable();      /** Foreign key referencing the 'rid' in the 'rules' table, establishing a relationship between the wallet and a specific rule. */
            $table                                                  /** Enum column for the status of the wallets, which can be either 'Active' or 'Inactive' (defaults to 'Active') */ 
                ->enum('status', ['Active', 'Inactive'])
                ->default('Active');
            $table->timestamps();                                   /** Timestamps for created_at and updated_at */ 

            $table                                                  /** Foreign Key constraint for webhook_id referencing 'webhooks' */
                ->foreign('webhook_id')
                ->references('webhid')
                ->on('webhooks')
                ->onDelete('set null');
            $table                                                  /** Foreign Key constraint for rule_id referencing 'rules' */
                ->foreign('rule_id')
                ->references('rid')
                ->on('rules')
                ->onDelete('set null');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
