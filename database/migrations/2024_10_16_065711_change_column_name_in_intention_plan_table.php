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
        Schema::table('intention_plan', function (Blueprint $table) {

            $table->renameColumn('ninety_day_intention', 'intention_option_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intention_plan', function (Blueprint $table) {

            $table->renameColumn('intention_option_id', 'ninety_day_intention');

        });
    }
};
