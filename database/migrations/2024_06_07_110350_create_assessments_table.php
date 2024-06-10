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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->integer('page')->default(0);;
            $table->integer('sa')->default(0);
            $table->integer('ma')->default(0);
            $table->integer('jo')->default(0);
            $table->integer('lu')->default(0);
            $table->integer('ven')->default(0);
            $table->integer('mer')->default(0);
            $table->integer('so')->default(0);
            $table->integer('de')->default(0);
            $table->integer('dom')->default(0);
            $table->integer('fe')->default(0);
            $table->integer('gre')->default(0);
            $table->integer('lun')->default(0);
            $table->integer('nai')->default(0);
            $table->integer('ne')->default(0);
            $table->integer('pow')->default(0);
            $table->integer('sp')->default(0);
            $table->integer('tra')->default(0);
            $table->integer('van')->default(0);
            $table->integer('wil')->default(0);
            $table->integer('g')->default(0);
            $table->integer('s')->default(0);
            $table->integer('c')->default(0);
            $table->integer('em')->default(0);
            $table->integer('ins')->default(0);
            $table->integer('int')->default(0);
            $table->integer('mov')->default(0);


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('assessments');
    }
};
