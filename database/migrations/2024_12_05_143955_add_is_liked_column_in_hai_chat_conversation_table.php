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

            $table->tinyInteger('is_liked')->nullable()->comment('1 like, 0 for dislike, 2 for 2nd dislike and 3 for ubmit query to admin');

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

            $table->dropColumn('is_liked');

        });
    }
};
