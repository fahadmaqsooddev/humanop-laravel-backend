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
        Schema::create('family_matrix_configurations', function (Blueprint $table) {
            $table->id();

            $table->string('grid_name');      // Grid name
            $table->string('color_code');     // e.g. #FF0000 or rgb()
            $table->text('text')->nullable(); // Description or content

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
        Schema::dropIfExists('family_matrix_configurations');
    }
};
