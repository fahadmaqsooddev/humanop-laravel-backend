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
        Schema::table('chatbot', function (Blueprint $table) {

            $table->boolean('is_published')->default(0)->comment('1 for published chat bot and 0 for unpublished chat bot');

            $table->renameColumn('publish', 'publish_path');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chatbot', function (Blueprint $table) {

            $table->dropColumn(['is_published']);

            $table->renameColumn('publish_path', 'publish');

        });
    }
};
