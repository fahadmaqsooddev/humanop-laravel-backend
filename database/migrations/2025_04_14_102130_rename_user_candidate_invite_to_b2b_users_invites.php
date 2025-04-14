<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('user_candidate_invite', 'b2b_users_invites');
    }

    public function down(): void
    {
        Schema::rename('b2b_users_invites', 'user_candidate_invite');
    }
};
