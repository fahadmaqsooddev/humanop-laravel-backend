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
        Schema::create('b2b_tasks_responsibilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_template_id')->nullable();
            $table->string('name');
            $table->string('tag1');
            $table->string('tag2');
            $table->string('tag3');

            $table->foreign('role_template_id')->references('id')->on('role_templates')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_tasks_responsibilities');
    }
};
