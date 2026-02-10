<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assign_family_matrix_relationships', function (Blueprint $table) {

            if (!Schema::hasColumn('assign_family_matrix_relationships', 'consent')) {
                $table->tinyInteger('consent')
                    ->default(0)
                    ->comment('0 = pending, 1 = approved, 2 = rejected')
                    ->after('relationship_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assign_family_matrix_relationships', function (Blueprint $table) {


            if (Schema::hasColumn('assign_family_matrix_relationships', 'consent')) {
                $table->dropColumn('consent');
            }
        });
    }
};
