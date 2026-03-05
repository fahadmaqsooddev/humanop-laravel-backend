<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('impact_contributions')) {
            Schema::create('impact_contributions', function (Blueprint $table) {
                $table->bigIncrements('id');

               
                $table->unsignedBigInteger('user_id');
                $table->index('user_id'); // index for faster queries
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                $table->unsignedBigInteger('impact_project_id');
                $table->index('impact_project_id'); // index for faster queries
                $table->foreign('impact_project_id')->references('id')->on('impact_projects')->onDelete('cascade');

                $table->integer('hp_contributed');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::table('impact_contributions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['impact_project_id']);
        });

        Schema::dropIfExists('impact_contributions');
    }
};