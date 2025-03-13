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
            $table->tinyInteger('role')->nullable()->comment('1: candidate, 0: team member');
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

            $table->dropColumn('role');
        });
    }
};
