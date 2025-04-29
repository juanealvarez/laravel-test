<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // 'easymoney' o 'superwalletz'
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->string('status');
            $table->string('external_id')->nullable(); // id de la plataforma de pago
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
