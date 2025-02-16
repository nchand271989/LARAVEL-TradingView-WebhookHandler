<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('webhook_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('webhook_id');   /** Foreign key referencing the 'webhid' in the 'webhooks' table, associating each attribute with a specific webhook */ 
            $table->string('attribute_name');           /** Name of the attribute, used for identifying the type of attribute (e.g., 'url', 'method', etc.) */
            $table->string('attribute_value');          /** Value of the attribute, storing the webhook value for the specified attribute name */
            $table->timestamps();                       /** Timestamps for created_at and updated_at */ 
            $table                                      /** Foreign key constraint for 'webhook_id' */
                ->foreign('webhook_id')
                ->references('webhid')
                ->on('webhooks')
                ->onDelete('cascade');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('webhook_attributes');
    }
};
