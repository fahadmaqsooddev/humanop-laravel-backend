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
        Schema::create('posts', function (Blueprint $table) {

            $table->id();

            $table->text('description')->nullable();
            $table->tinyInteger('approve')->default('1')->comment('1 = approved, 0 = unapproved');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('upload_id')->nullable()->constrained();

            $table->softDeletes();

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
        Schema::dropIfExists('posts');
    }
};
