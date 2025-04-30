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
        Schema::table('users', function (Blueprint $table) {

            $table->integer('complete_assessment_walkthrough')->default(0)->comment('0: not complete, 1: complete assessment walkthrough');
            $table->integer('complete_tutorial')->default(0)->comment('0: not complete, 1: complete tutorial');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('complete_assessment_walkthrough');
            $table->dropColumn('complete_tutorial');
        });
    }
};
