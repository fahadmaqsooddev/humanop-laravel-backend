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
        Schema::table('version_control_descriptions', function (Blueprint $table) {
            //
            $table->string('version_heading')->comment('0: issue fixed 1:new feature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('version_control_descriptions', function (Blueprint $table) {
            //
            $table->dropColumn('version_heading');
        });
    }
};
