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
        Schema::create('achievement', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 100)->nullable(false);
            $table->foreignId('achievement_type_id')->constrained(
                table: 'achievement_type',
                indexName: 'achievement_type_id'
            );
            $table->integer('level')->nullable(false);
            $table->unique(['name', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievement', function (Blueprint $table) {
            $table->dropForeign('achievement_type_id');
        });
        Schema::dropIfExists('achievement');
    }
};
