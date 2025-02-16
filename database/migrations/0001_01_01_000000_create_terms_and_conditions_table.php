<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->unsignedBigInteger('tid')->primary();   /** Primary key for the terms_and_conditions table, using an unsigned big integer */
            $table->text('content');                        /** The actual content of the terms and conditions, stored as text since it can be lengthy */
            $table->string('version');                      /** The version of the terms and conditions, used to track different revisions or changes */
            $table->timestamps();                           /** Timestamps for created_at and updated_at */
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');
    }
};
