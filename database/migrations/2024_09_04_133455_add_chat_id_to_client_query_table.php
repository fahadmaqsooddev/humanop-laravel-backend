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
        Schema::table('client_query', function (Blueprint $table) {
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->foreign('chat_id')->references('id')->on('haichat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('client_query', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
            $table->dropColumn('chat_id');
        });
    }
};
