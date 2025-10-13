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
        Schema::create('message_thread_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_thread_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->tinyInteger('role')->default(2)->comment('0 => owner, 1 => admin, 2 => member');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('muted_until')->nullable();
            $table->timestamps();
            $table->unique(['message_thread_id','user_id']);

            $table->foreign('message_thread_id')->references('id')->on('message_threads')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_thread_participants');
    }
};
