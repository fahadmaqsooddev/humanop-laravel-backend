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
        Schema::create('lifetime_deal_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title_for_beta_breaker')->nullable();
            $table->string('title_for_freemium')->nullable();
            $table->longText('description_for_beta_breaker')->nullable();
            $table->longText('description_for_freemium')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 => enable, 0 => disable');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('lifetime_deal_banners');
    }
};
