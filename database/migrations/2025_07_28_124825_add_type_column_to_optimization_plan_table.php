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
        Schema::table('optimization_plan', function (Blueprint $table) {

            $table->integer('type')->default(1)->comment('1: freemium, 2: paid');

        });

        Schema::table('action_plans', function (Blueprint $table) {

            $table->integer('type')->default(1)->comment('1: freemium, 2: paid');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('optimization_plan', function (Blueprint $table) {

            $table->dropColumn('type');

        });

        Schema::table('action_plans', function (Blueprint $table) {

            $table->dropColumn('type');

        });
    }
};
