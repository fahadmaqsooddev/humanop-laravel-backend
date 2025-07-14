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
        Schema::table('user_daily_tips', function (Blueprint $table) {

            $table->integer('favorite_tip')->default(1)->comment('1: not favorite, 2: favorite');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_daily_tips', function (Blueprint $table) {

            $table->dropColumn('favorite_tip');
        });
    }
};
