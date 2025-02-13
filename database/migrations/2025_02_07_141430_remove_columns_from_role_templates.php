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
        Schema::table('role_templates', function (Blueprint $table) {
            //
            $table->dropColumn(['title', 'description','subscription_type']); // Remove multiple columns

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_templates', function (Blueprint $table) {
            //
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('subscription_type')->default('Freemium')->nullable();
        });
    }
};
