<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('nome', 255);
            $table->string('email', 255);
            $table->string('telefone', 20);
            $table->timestamps(); // created_at e updated_at
        });
        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->string('parcela_numero');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->string('status_pagamento')->default('pendente');
            $table->timestamps();
        });
        
    }
    
};
