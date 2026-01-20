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
        Schema::create('hotspot_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('hotspot_id')->nullable();
            $table->integer('hotspot_score')->default(0);
            $table->string('shift_interval')->nullable();
            $table->string('names')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('assessment_id')
                ->references('id')
                ->on('assessments')
                ->onDelete('cascade');

            $table->foreign('hotspot_id')
                ->references('id')
                ->on('hotspots')
                ->onDelete('cascade');
              
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
        Schema::dropIfExists('hotspots_user');
    }
};
