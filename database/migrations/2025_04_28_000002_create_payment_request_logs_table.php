<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->json('request_body');
            $table->json('response_body')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_request_logs');
    }
}
