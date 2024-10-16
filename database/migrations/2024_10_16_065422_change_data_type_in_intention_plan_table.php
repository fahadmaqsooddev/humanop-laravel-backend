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

            $table->unsignedBigInteger('ninety_day_intention')->change();

            $table->foreign('ninety_day_intention')->references('id')->on('intention_options');

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

            $table->string('ninety_day_intention')->change();

        });
    }
};
