<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // gender ko smallInt unsigned me convert karo
            $table->unsignedInteger('gender')->nullable()->comment('0 = male, 1 = female')->change();
            // Index add karo
            $table->index('gender');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Wapas varchar me convert karo
            $table->string('gender')->comment('Gender of the user, male/female')->change();
            // Index drop karo
            $table->dropIndex(['gender']);
        });
    }
};
