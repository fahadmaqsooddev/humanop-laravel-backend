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
        Schema::create('task_responsibilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_template_id');
            $table->string('tags');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('role_template_id')->references('id')->on('role_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_responsibilities');
    }
};
