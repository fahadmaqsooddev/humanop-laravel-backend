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
        Schema::create('chat_bots', function (Blueprint $table) {

            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('description', 1000)->nullable();
            $table->float('temperature')->nullable()->default(0.1);
            $table->unsignedInteger('max_tokens')->nullable()->default(500);
            $table->unsignedInteger('chunks')->nullable()->default(10);
            $table->tinyInteger('model_type')->default(1)->nullable();
            $table->text('prompt')->nullable();
            $table->text('restriction')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained();
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
        Schema::dropIfExists('chat_bots');
    }
};
