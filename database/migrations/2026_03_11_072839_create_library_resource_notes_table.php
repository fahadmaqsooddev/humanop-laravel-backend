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
        Schema::create('library_resource_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('resource_id')->index();
            $table->text('notes');
            $table->timestamps();

            // Explicit foreign key names
            $table->foreign('user_id', 'library_notes_user_id_fk')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('resource_id', 'library_notes_resource_id_fk')
                  ->references('id')
                  ->on('library_resources')
                  ->onDelete('cascade');

            $table->unique(['user_id', 'resource_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('library_resource_notes');
    }
};
