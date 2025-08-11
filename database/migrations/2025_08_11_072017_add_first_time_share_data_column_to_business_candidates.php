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
        Schema::table('business_candidates', function (Blueprint $table) {

            $table->integer('first_time_share_data')->default(0)->comment('0: not shared, 1: shared');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_candidates', function (Blueprint $table) {

            $table->dropColumn('first_time_share_data');

        });
    }
};
