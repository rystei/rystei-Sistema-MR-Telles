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
            $table->unsignedBigInteger('user_id'); // Relacionamento com a tabela de usuários
            $table->string('lote', 20); // Identificação do lote (formato de data/hora)
            $table->integer('parcela_numero'); // Número da parcela
            $table->decimal('valor', 10, 2); // Valor da parcela
            $table->string('descricao', 255); // Descrição da parcela
            $table->date('data_vencimento'); // Data de vencimento da parcela
            $table->enum('status_pagamento', ['pendente', 'pago', 'atrasado'])->default('pendente'); // Status do pagamento
            $table->date('data_pagamento')->nullable(); // Data de pagamento (caso a parcela tenha sido paga)
            $table->timestamps();

            // Chave estrangeira para a tabela de usuários
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('controle_financeiro');
    }
};
