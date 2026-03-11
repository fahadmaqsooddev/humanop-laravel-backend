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
            
            $table->foreignId('resource_id')
                ->constrained('library_resources')
                ->cascadeOnDelete()
                ->index();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->index();

            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('library_resource_notes');
    }
};
