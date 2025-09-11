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
        Schema::create('user_invite_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invite_id')->nullable();
            $table->integer('role')->default(\App\Enums\Admin\Admin::CLIENT_INVITE_ROLE)->comment('1: B2C invite, 2: Client invite');

            $table->foreign('invite_id')->references('id')->on('user_invites')->onDelete('cascade');
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

        Schema::dropIfExists('user_invite_log');
    }
};
