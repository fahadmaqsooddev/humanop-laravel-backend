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
        Schema::create('humanop_shop_resources', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('upload_id')->nullable();
            $table->foreign('upload_id')->references('id')->on('uploads')->onDelete('cascade');
            $table->foreignId('humanop_shop_category_id')->nullable()->constrained();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('source_id')->nullable();
            $table->string('source_url')->nullable();
            $table->string('embed_link')->nullable();
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
        Schema::dropIfExists('humanop_shop_resources');
    }
};
