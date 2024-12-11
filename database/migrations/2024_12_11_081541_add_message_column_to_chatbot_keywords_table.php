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

        Schema::table('hai_chat_prompts', function (Blueprint $table) {

            $table->dropColumn('keyword_restriction_message');

        });

        Schema::table('chatbot_keywords', function (Blueprint $table) {

            $table->string('message')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hai_chat_prompts', function (Blueprint $table) {

            $table->string('keyword_restriction_message')->nullable();

        });

        Schema::table('chatbot_keywords', function (Blueprint $table) {

            $table->dropColumn('message');

        });
    }
};
