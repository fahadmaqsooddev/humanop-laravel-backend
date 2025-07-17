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
        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            //
            $table->renameColumn('buy_from', 'price');
            $table->renameColumn('point_price', 'point');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            //
            $table->renameColumn('price', 'buy_from');
            $table->renameColumn('point', 'point_price');
        });
    }
};
