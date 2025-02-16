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
            $table->unsignedBigInteger('webhook_id');       /** Foreign key referencing the 'webhid' in the 'webhooks' table, associating each rule with a specific webhook */ 
            $table->unsignedBigInteger('rule_id');          /** Foreign key referencing the 'rid' in the 'rules' table, associating each rule with a webhook */

            // Define foreign key constraints
            $table                                          /** Foreign key constraint for 'webhook_id' */
                ->foreign('webhook_id')
                ->references('webhid')
                ->on('webhooks')
                ->onDelete('cascade');

            $table                                          /** Foreign key constraint for 'rule_id' */
                ->foreign('rule_id')
                ->references('rid')
                ->on('rules')
                ->onDelete('cascade'); 

            
            $table->primary(['webhook_id', 'rule_id']);     /** Define a composite primary key using both 'webhook_id' and 'rule_id' */ 
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('webhook_rules');
    }
};
