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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->datetime('start')->nullable();
            $table->datetime('end')->nullable();
            $table->boolean('all_day')->default(false); // Adicionado diretamente aqui
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->boolean('user_id')->default(0); // Adicionado diretamente aqui
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
