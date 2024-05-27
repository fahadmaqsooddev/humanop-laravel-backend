<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('age_min')->nullable();
            $table->tinyInteger('age_max')->nullable();
            $table->string('gender')->nullable();
            $table->dateTime('signup_date')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('stripe_id')->nullable();
            $table->tinyInteger('is_admin')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
