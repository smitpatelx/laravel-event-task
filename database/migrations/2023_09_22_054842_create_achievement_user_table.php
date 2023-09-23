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
        Schema::create('achievement_user', function (Blueprint $table) {
            $table->id()->increments('id');
            $table->timestamps();
            $table->foreignId('achievement_id')->constrained(
                table: 'achievement',
                indexName: 'achievement_id_fk_achievement_user'
            );
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'user_id_fk_achievement_user'
            );
            $table->unique(['achievement_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievement_user', function (Blueprint $table) {
            $table->dropForeign('achievement_id_fk_achievement_user');
            $table->dropForeign('user_id_fk_achievement_user');
        });
        Schema::dropIfExists('achievement_user');
    }
};
