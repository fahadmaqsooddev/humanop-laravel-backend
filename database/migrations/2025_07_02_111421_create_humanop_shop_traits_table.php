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
        Schema::create('humanop_shop_traits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('humanop_shop_resource_id');
            $table->string('trait_name')->nullable();
            $table->foreign('humanop_shop_resource_id')->references('id')->on('humanop_shop_resources')->onDelete('cascade');
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
        Schema::dropIfExists('humanop_shop_traits');
    }
};
