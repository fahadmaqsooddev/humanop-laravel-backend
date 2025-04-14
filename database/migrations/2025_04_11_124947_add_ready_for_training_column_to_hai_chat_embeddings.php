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
        Schema::table('hai_chat_embeddings', function (Blueprint $table) {

            $table->tinyInteger('ready_for_training')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hai_chat_embeddings', function (Blueprint $table) {

            $table->dropColumn(['ready_for_training']);

        });
    }
};
