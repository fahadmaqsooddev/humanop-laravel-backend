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
        Schema::create('stripe_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_key')->nullable();
            $table->string('public_key')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_email')->nullable();

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
        Schema::dropIfExists('stripe_settings');
    }
};
