<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('Cliente vinculado');
            $table->string('numero_processo')->unique();
            $table->text('descricao');
            $table->string('status_atual')->default('protocolado');
            $table->json('historico')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processos');
    }
};
