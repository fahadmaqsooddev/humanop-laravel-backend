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
        Schema::table('points', function (Blueprint $table) {

            $table->double('point', 5, 2)->change();

        });

        Schema::table('point_logs', function (Blueprint $table) {

            $table->double('point', 5, 2)->change();

            $table->boolean('is_added')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('points', function (Blueprint $table) {

            $table->integer('point')->change();

        });

        Schema::table('point_logs', function (Blueprint $table) {

            $table->integer('point')->change();

            $table->dropColumn(['is_added']);

        });
    }
};
