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
        Schema::table('point_logs', function (Blueprint $table) {
            $table->string('plan')->nullable()->comment('1: freemium,2: core,3: premium');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_logs', function (Blueprint $table) {
            $table->dropColumn('plan');
        });
    }
};
