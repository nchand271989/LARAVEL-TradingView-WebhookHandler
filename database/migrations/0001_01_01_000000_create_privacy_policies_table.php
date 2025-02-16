<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('privacy_policies', function (Blueprint $table) {
            $table->unsignedBigInteger('pid')->primary();   /** Primary key for the privacy_policies table, using an unsigned big integer */
            $table->text('content');                        /** The actual content of the privacy policy, stored as text since it may be lengthy */
            $table->string('version');                      /** The version of the privacy policy, used to track different revisions or changes */
            $table->timestamps();                           /** Timestamps for created_at and updated_at */
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('privacy_policies');
    }
};
