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
        
        Schema::table('plans', function (Blueprint $table) {

            $table->integer('plan_type')->comment('0:B2C 1:B2B')->default(0);
            $table->integer('team_members')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['plan_type', 'team_members']);
        });
    }
};
