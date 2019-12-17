<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_queue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('to');
            $table->string('subject');
            $table->longText('message');
            $table->enum('status', ['SENT', 'PENDING', 'ERROR'])->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_queue');
    }
}
