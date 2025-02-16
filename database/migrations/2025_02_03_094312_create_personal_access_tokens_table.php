<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();                                   /** Primary key for the personal access token table, auto-incrementing integer */
            $table->morphs('tokenable');                    /** Morphable relationship, used to associate this token with a model (e.g., User, Admin) */
            $table->string('name');                         /** The name of the token, typically used to identify the purpose or description of the token */
            $table->string('token', 64)->unique();          /** The actual token string, 64 characters in length, unique for each token */
            $table->text('abilities')->nullable();          /** A JSON-encoded array of abilities, defines the permissions or actions allowed for this token (optional) */
            $table->timestamp('last_used_at')->nullable();  /** The timestamp of the last time this token was used */
            $table->timestamp('expires_at')->nullable();    /** The timestamp when the token will expire, if applicable */
            $table->timestamps();                           /** Timestamps for created_at and updated_at columns */
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
