<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the columns if they already exist
            if (Schema::hasColumn('users', 'tid')) {
                $table->dropColumn('tid');
            }

            if (Schema::hasColumn('users', 'pid')) {
                $table->dropColumn('pid');
            }
            
            // Add the tid and pid columns for terms and privacy policy after profile_photo_path
            $table->uuid('tid')->nullable()->after('profile_photo_path');
            $table->uuid('pid')->nullable()->after('tid');

            // Add foreign key constraints referencing the uuid columns
            $table->foreign('tid')->references('tid')->on('terms_and_conditions')->onDelete('set null');
            $table->foreign('pid')->references('pid')->on('privacy_policies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign keys and columns
            $table->dropForeign(['tid']);
            $table->dropForeign(['pid']);
            $table->dropColumn(['tid', 'pid']);
        });
    }
};
