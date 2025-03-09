<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


    class AddLoteToControleFinanceiroTable extends Migration
    {
        public function up()
        {
            Schema::table('controle_financeiro', function (Blueprint $table) {
                $table->string('lote', 20)->after('user_id');
            });
        }
    }

