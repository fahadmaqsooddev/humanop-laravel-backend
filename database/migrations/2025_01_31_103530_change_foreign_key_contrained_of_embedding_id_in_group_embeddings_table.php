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
        Schema::table('group_embeddings', function (Blueprint $table) {

            $table->dropConstrainedForeignId('embedding_id');
//            $table->dropColumn('embedding_id');

        });

        Schema::table('group_embeddings', function (Blueprint $table) {

            $table->unsignedBigInteger('embedding_id')->nullable();
            $table->foreign('embedding_id')->references('id')->on('hai_chat_embeddings');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_embeddings', function (Blueprint $table) {

            $table->dropConstrainedForeignId('embedding_id');

        });

        Schema::table('group_embeddings', function (Blueprint $table) {

            $table->unsignedBigInteger('embedding_id')->nullable();
            $table->foreign('embedding_id')->references('id')->on('hai_chat_embeddings');

        });
    }
};
