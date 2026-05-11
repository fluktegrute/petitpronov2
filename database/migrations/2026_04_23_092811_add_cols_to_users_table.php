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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('password');
            $table->foreignId('winner_team_id')->nullable()->after('password')->constrained('teams')->nullOnDelete();
            $table->integer('boosts_remaining')->default(config('app.initial_booster_quantity'))->after('password');
            $table->integer('exact_count')->default(0)->after('password');
            $table->integer('trend_count')->default(0)->after('password');
            $table->integer('prono_count')->default(0)->after('password');
            $table->integer('total_points')->default(0)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('boosts_remaining');
            $table->dropColumn('winner_team_id');
            $table->dropColumn('total_points');
        });
    }
};
