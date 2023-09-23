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
        Schema::create('badge', function (Blueprint $table) {
            $table->id()->increments('id');
            $table->timestamps();
            $table->string('name', 100)->nullable(false);
            $table->integer('no_of_achievement')->nullable(false);
            $table->unique(['name', 'no_of_achievement']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge');
    }
};
