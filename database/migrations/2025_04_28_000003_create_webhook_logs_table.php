<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookLogsTable extends Migration
{
    public function up()
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->json('payload');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webhook_logs');
    }
}
