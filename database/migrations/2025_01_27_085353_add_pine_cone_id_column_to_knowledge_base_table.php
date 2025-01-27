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
        Schema::table('knowledge_base', function (Blueprint $table) {

            $table->string('pine_cone_id')->nullable();

        });

        Schema::table('embeddings', function (Blueprint $table) {

            $table->string('pine_cone_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_base', function (Blueprint $table) {

            $table->dropColumn(['pine_cone_id']);

        });

        Schema::table('embeddings', function (Blueprint $table) {

            $table->dropColumn(['pine_cone_id']);

        });
    }
};
