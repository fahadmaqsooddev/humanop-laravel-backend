<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
    
           Schema::create('impact_contributions', function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('impact_project_id')->constrained('impact_projects')->cascadeOnDelete();

                $table->integer('hp_contributed');
                $table->timestamps();
            });
    }

    public function down()
    {
         Schema::dropIfExists('impact_contributions');
    }
};