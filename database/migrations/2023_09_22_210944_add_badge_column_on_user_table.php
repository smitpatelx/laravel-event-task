<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add badge column on user table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('badge_id')->nullable(true)->constrained(
                table: 'badge',
                indexName: 'badge_id_fk_badge_user'
            );
            $table->unique('badge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove badge column on user table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('badge_id_fk_badge_user');
            $table->dropColumn('badge_id');
        });
    }
};
