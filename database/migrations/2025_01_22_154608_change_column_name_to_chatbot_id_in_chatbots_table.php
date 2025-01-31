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
        Schema::table('chatbot_keywords', function (Blueprint $table) {

            $table->dropConstrainedForeignId('chat_bot_id');

        });

        Schema::table('chatbot_keywords', function (Blueprint $table) {

            $table->unsignedBigInteger('chatbot_id')->nullable();

            $table->foreign('chatbot_id')->references('id')->on('chatbot');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chatbot_keywords', function (Blueprint $table) {

            $table->unsignedBigInteger('chat_bot_id')->nullable();

            $table->foreign('chat_bot_id')->references('id')->on('chat_bots');

        });

        Schema::table('chatbot_keywords', function (Blueprint $table) {

//            $table->dropColumn(['chatbot_id']);

            $table->dropConstrainedForeignId('chatbot_id');

        });
    }
};
