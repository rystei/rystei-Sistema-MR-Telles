<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1) Alterar a tabela controle_financeiro
        Schema::table('controle_financeiro', function (Blueprint $table) {
            // Remove a foreign key existente (depende do nome que foi gerado no schema)
            $table->dropForeign(['cliente_id']);

            // Renomeia a coluna cliente_id para user_id
            $table->renameColumn('cliente_id', 'user_id');

            // Cria a nova foreign key apontando para a tabela users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2) Alterar a tabela parcelas
        Schema::table('parcelas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->renameColumn('cliente_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Reverte as mudanças, caso seja necessário dar rollback
        Schema::table('controle_financeiro', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });

        Schema::table('parcelas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }
};

