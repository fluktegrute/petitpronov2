<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_match_reminder')->default(false)->after('avatar_path');
            $table->boolean('notify_daily_recap')->default(false)->after('notify_match_reminder');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_match_reminder', 'notify_daily_recap']);
        });
    }
};
