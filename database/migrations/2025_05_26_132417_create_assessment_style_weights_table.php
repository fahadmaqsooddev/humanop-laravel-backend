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
        Schema::create('assessment_style_weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id')->default(0);
            $table->integer('page')->default(0);;
            $table->integer('sa_weight')->default(0);
            $table->integer('ma_weight')->default(0);
            $table->integer('jo_weight')->default(0);
            $table->integer('lu_weight')->default(0);
            $table->integer('ven_weight')->default(0);
            $table->integer('mer_weight')->default(0);
            $table->integer('so_weight')->default(0);

            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
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
        Schema::dropIfExists('assessment_style_weights');
    }
};
