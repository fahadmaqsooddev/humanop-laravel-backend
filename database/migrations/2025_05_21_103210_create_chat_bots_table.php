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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('chat_bots');

        Schema::create('chat_bots', function (Blueprint $table) {

            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->float('temperature')->nullable();
            $table->unsignedSmallInteger('max_tokens')->nullable();
            $table->tinyInteger('chunks')->nullable();
            $table->tinyInteger('model_type')->nullable();
            $table->tinyInteger('is_connected')->default(0);
            $table->softDeletes();
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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('chat_bots');
    }
};
