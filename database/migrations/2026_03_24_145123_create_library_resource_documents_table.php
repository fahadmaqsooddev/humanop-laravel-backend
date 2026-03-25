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
        Schema::create('library_resource_documents', function (Blueprint $table) {
            $table->id();
          
            $table->foreignId('resource_id')
                  ->constrained('library_resources')
                  ->onDelete('cascade');

           
            $table->foreignId('document_id')
                  ->constrained('uploads')
                  ->onDelete('cascade');

            $table->tinyInteger('download_document')->default(0);
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
        Schema::dropIfExists('library_resource_documents');
    }
};
