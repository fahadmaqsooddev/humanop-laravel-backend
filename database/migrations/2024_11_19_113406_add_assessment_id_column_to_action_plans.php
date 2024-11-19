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
        Schema::table('action_plans', function (Blueprint $table) {

            $table->unsignedBigInteger('assessment_id')->nullable();

            $table->foreign('assessment_id')->references('id')->on('assessments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_plans', function (Blueprint $table) {

            $table->dropConstrainedForeignId('assessment_id');

        });
    }
};
