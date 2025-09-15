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
        Schema::create('signup_screens', function (Blueprint $table) {
            $table->id();
            $table->string('screen_name')->nullable();
            $table->longText('description')->nullable();
            $table->integer('screen_type')->default(1)->comment('1:B2C, 2:B2B');
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
        Schema::dropIfExists('signup_screens');
    }
};
