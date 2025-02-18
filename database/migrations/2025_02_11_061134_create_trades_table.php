<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();                /** The primary key of the table, an auto-incrementing ID for each trade. */
            $table->unsignedBigInteger('webhook_id')->nullable();       /** Foreign key to link to the 'webhooks' table, indicating the webhook associated with the trade. */
            $table->unsignedBigInteger('strategy_id')->nullable();      /** Foreign key to link to the 'strategies' table, indicating the strategy used in the trade. */
            $table->unsignedBigInteger('exchange_id')->nullable();      /** Foreign key to link to the 'exchanges' table, indicating the exchange involved in the trade. */
            $table->unsignedBigInteger('currency_id')->nullable();      /** Foreign key to link to the 'currencies' table, indicating the currency used in the trade. */
            $table->unsignedBigInteger('rule_id')->nullable();          /** Foreign key to link to the 'rules' table, indicating the trading rule applied. */
            $table->unsignedBigInteger('wallet_id')->nullable();        /** Foreign key to link to the 'wallets' table, indicating the wallet that executed the trade. */
            $table->string('positionType');                             /** The type of position, which can either be 'Long' or 'Short'. */
            $table->decimal('quantity', 16, 3);                         /** The quantity of the asset being traded, stored as a decimal with 16 digits and 3 decimal places. */
            $table                                                      /** Enum column for the timeframe, which can be either '1m', '3m', '5m', '10m', '15m', '30m', 'H', 'D' or 'M') */ 
                ->enum('timeframe', 
                    ['1m', '3m', '5m', '10m', '15m', '30m', 'H', 'D', 'M']
                );
            $table                                                      /** The price at which the position was opened, stored as a decimal with 16 digits and 8 decimal places. */
                ->decimal('openingPrice', 16, 8);
            $table                                                      /** The timestamp when the trade was opened. */
                ->timestamp('openingTime');
            $table                                                      /** The price at which the position was closed, stored as a decimal. This field is nullable. */
                ->decimal('closingPrice', 16, 8)
                ->nullable();
            $table                                                      /** The timestamp when the trade was closed. This field is nullable. */
                ->timestamp('closingTime')
                ->nullable();
            $table                                                      /** Additional comments or notes related to the trade. This field is nullable. */ 
                ->text('comments')
                ->nullable();
            $table                                                      /** Enum column for the status of the trades, which can be either 'Active' or 'Inactive' (defaults to 'Active') */ 
                ->enum('status', ['Active', 'Closed'])
                ->default('Active');
            $table                                                      /** Timestamps for created_at and updated_at */ 
                ->timestamps();

            $table                                                      /** Foreign key constraint for webhook_id referencing 'webhooks' */
                ->foreign('webhook_id')
                ->references('webhid')
                ->on('webhooks')
                ->onDelete('set null');
            $table                                                      /** Foreign key constraint for strategy_id referencing 'strategies' */
                ->foreign('strategy_id')
                ->references('stratid')
                ->on('strategies')
                ->onDelete('set null');
            $table                                                      /** Foreign key constraint for exchange_id referencing 'exchanges' */
                ->foreign('exchange_id')
                ->references('exid')
                ->on('exchanges')
                ->onDelete('set null');
            $table                                                      /** Foreign key constraint for currency_id referencing 'currencies' */
                ->foreign('currency_id')
                ->references('curid')
                ->on('currencies')
                ->onDelete('set null');
            $table                                                      /** Foreign key constraint for rule_id referencing 'rules' */
                ->foreign('rule_id')
                ->references('rid')
                ->on('rules')
                ->onDelete('set null');
            $table                                                      /** Foreign key constraint for wallet_id referencing 'wallets' */
                ->foreign('wallet_id')
                ->references('wltid')
                ->on('wallets')
                ->onDelete('set null');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
