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

        Schema::table('hai_chat_conversation', function (Blueprint $table) {

//            $table->foreignId('chat_bot_id')->nullable()->constrained();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hai_chat_conversation', function (Blueprint $table) {

//            $table->dropConstrainedForeignId('chat_bot_id');

        });
    }
};
