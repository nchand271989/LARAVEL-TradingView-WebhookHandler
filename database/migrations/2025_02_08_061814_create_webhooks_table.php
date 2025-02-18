<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->unsignedBigInteger('webhid')->primary();            /** Store Snowflake ID as an integer for unique webhook identification */
            $table->string('name');                                     /** Name of the webhook, typically describing its purpose or function */
            $table->unsignedBigInteger('strategy_id')->nullable();      /** Foreign key referencing 'stratid' from the 'strategies' table, indicating which strategy the webhook is associated with */ 
            $table->unsignedBigInteger('exchange_id');                  /** Foreign key referencing 'exid' from the 'exchanges' table, indicating the exchange for the webhook */
            $table->unsignedBigInteger('currency_id');                  /** Foreign key referencing 'curid' from the 'currencies' table, indicating the currency for the webhook */
            $table->unsignedBigInteger('createdBy')->nullable();        /** Foreign key referencing 'id' from the 'users' table, indicating the user who created the webhook */
            $table->unsignedBigInteger('lastUpdatedBy')->nullable();    /** Foreign key referencing 'id' from the 'users' table, indicating the user who last updated the webhook */
            $table->timestamps();                                       /** Timestamps for created_at and updated_at */ 
            $table                                                      /** Enum column for the status of the webhook, which can be either 'Active' or 'Inactive' (defaults to 'Active') */ 
                ->enum('status', ['Active', 'Inactive'])
                ->default('Active');

            $table                                                      /** Foreign key relationship for 'strategy_id' */
                ->foreign('strategy_id')
                ->references('stratid')
                ->on('strategies')
                ->onDelete('set null');
            $table                                                      /** Foreign key relationship for 'createdBy' */
                ->foreign('createdBy')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table                                                      /** Foreign key relationship for 'lastUpdatedBy' */
                ->foreign('lastUpdatedBy')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
