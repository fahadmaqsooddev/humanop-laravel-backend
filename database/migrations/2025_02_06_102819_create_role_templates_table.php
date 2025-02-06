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
        Schema::create('role_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('role_name')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('subscription_type')->default('Freemium')->nullable();
            $table->string('min_point')->nullable();
            $table->string('max_point')->nullable();
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
        Schema::dropIfExists('role_templates');
    }
};
