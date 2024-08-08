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
        Schema::create('connections', function (Blueprint $table) {

            $table->id();

            $table->tinyInteger('status')->nullable()->default(0)->comment('0 for pending, 1 for connect');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->unsignedBigInteger('friend_id')->nullable();
            $table->foreign('friend_id')->references('id')->on('users');

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
        Schema::dropIfExists('connections');
    }
};
