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
        Schema::table('user_share_assessment', function (Blueprint $table) {

            $table->integer('authentic_traits')->default(2)->comment('1: share, 2: not share');
            $table->integer('core_state')->default(2)->comment('1: share, 2: not share');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_share_assessment', function (Blueprint $table) {

            $table->dropColumn('authentic_traits');
            $table->dropColumn('core_state');
        });
    }
};
