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
        Schema::create('suggested_items_traits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suggested_item_id');
            $table->string('trait_name')->nullable();

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
        Schema::dropIfExists('suggested_items_traits');
    }
};
