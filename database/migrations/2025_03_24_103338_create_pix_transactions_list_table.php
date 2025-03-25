<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pix_transactions_list', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->decimal('total_amount', 10, 2);
            $table->text('pix_payload');
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pix_transactions_list');
    }
};