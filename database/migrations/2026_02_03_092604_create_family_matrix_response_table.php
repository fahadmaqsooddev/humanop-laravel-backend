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
        Schema::create('family_matrix_response', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->longText('vide_check_text')->nullable();
            $table->longText('physics_friction_analysis')->nullable();
            $table->longText('physics_flow_analysis')->nullable();
            $table->longText('system_hack_title')->nullable();
            $table->longText('system_hack_actionable_step')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('user_id');
            $table->index('target_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_matrix_response');
    }
};
