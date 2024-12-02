<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbot_keywords', function (Blueprint $table) {

            $table->id();
            $table->string('word')->nullable();
            $table->unsignedBigInteger('chatbot_id')->nullable();
            $table->foreign('chatbot_id')->references('id')->on('chatbot');
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
        Schema::dropIfExists('chatbot_keywords');
    }
};
