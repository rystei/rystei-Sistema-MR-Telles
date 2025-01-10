<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('controle_financeiro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id'); // Relacionamento com a tabela de clientes
            $table->integer('parcela_numero'); // Número da parcela
            $table->decimal('valor', 10, 2); // Valor da parcela
            $table->date('data_vencimento'); // Data de vencimento da parcela
            $table->enum('status_pagamento', ['pendente', 'pago', 'atrasado'])->default('pendente'); // Status do pagamento
            $table->date('data_pagamento')->nullable(); // Data de pagamento (se pago)
            $table->boolean('notificado')->default(false); // Flag para notificação
            $table->timestamps();
    
            // Chave estrangeira para a tabela de clientes
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('controle_financeiro');
    }
    
};
