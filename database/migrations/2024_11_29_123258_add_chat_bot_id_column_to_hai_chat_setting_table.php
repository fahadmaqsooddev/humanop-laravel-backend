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
        Schema::table('hai_chat_setting', function (Blueprint $table) {

            $table->unsignedBigInteger('chat_bot_id')->nullable();

            $table->foreign('chat_bot_id')->references('id')->on('chatbot');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hai_chat_setting', function (Blueprint $table) {

            $table->dropColumn(['chat_bot_id']);

        });
    }
};
