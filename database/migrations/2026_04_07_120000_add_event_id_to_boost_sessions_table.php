<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boost_sessions', function (Blueprint $table) {

            if (!Schema::hasColumn('boost_sessions', 'event_id')) {
                $table->unsignedBigInteger('event_id')->after('user_id');
            }
        });

        // Add index if not exists
        $indexExists = collect(
            DB::select("SHOW INDEX FROM boost_sessions WHERE Key_name = 'boost_event_id_idx'")
        )->isNotEmpty();

        if (!$indexExists) {
            Schema::table('boost_sessions', function (Blueprint $table) {
                $table->index('event_id', 'boost_event_id_idx');
            });
        }

        // Add foreign key if not exists
        $fkExists = collect(
            DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_NAME = 'boost_sessions' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME LIKE '%event_id%'")
        )->isNotEmpty();

        if (!$fkExists) {
            Schema::table('boost_sessions', function (Blueprint $table) {
                $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Drop foreign key if exists
        $foreignKeys = collect(
            DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_NAME = 'boost_sessions' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME LIKE '%event_id%'")
        );

        if ($foreignKeys->isNotEmpty()) {
            Schema::table('boost_sessions', function (Blueprint $table) use ($foreignKeys) {
                foreach ($foreignKeys as $fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                }
            });
        }

        // Drop index if exists
        $indexExists = collect(
            DB::select("SHOW INDEX FROM boost_sessions WHERE Key_name = 'boost_event_id_idx'")
        )->isNotEmpty();

        if ($indexExists) {
            Schema::table('boost_sessions', function (Blueprint $table) {
                $table->dropIndex('boost_event_id_idx');
            });
        }

        if (Schema::hasColumn('boost_sessions', 'event_id')) {
            Schema::table('boost_sessions', function (Blueprint $table) {
                $table->dropColumn('event_id');
            });
        }
    }
};
