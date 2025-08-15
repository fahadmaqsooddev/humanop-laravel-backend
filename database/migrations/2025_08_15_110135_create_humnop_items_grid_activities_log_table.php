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
        Schema::create('humnop_items_grid_activities_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_item_id')->nullable();
            $table->unsignedBigInteger('shop_item_id')->nullable();
            $table->unsignedBigInteger('suggested_item_id')->nullable();
            $table->string('grid_name')->nullable();

            $table->foreign('resource_item_id')->references('id')->on('library_resources')->onDelete('cascade');
            $table->foreign('shop_item_id')->references('id')->on('humanop_shop_resources')->onDelete('cascade');
            $table->foreign('suggested_item_id')->references('id')->on('suggested_items')->onDelete('cascade');
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
        Schema::dropIfExists('humnop_items_grid_activities_log');
    }
};
