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

            $table->renameColumn('content', 'fourteen_days_plan');

            $table->longText('ninty_days_plan')->nullable();
            $table->longText('day1_30')->nullable();
            $table->longText('day31_60')->nullable();
            $table->longText('day61_90')->nullable();

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

            $table->renameColumn('fourteen_days_plan', 'content');

            $table->dropColumn('ninty_days_plan');
            $table->dropColumn('day1_30');
            $table->dropColumn('day31_60');
            $table->dropColumn('day61_90');
        });
    }
};
