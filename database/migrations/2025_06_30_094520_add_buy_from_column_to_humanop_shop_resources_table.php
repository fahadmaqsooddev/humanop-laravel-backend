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
            $table->string('buy_from')->comment('1:price 2:point')->nullable();
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
            $table->dropColumn('buy_from');
        });
    }
};
