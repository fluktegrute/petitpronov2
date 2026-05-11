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
        Schema::create('matches_history', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('home_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->string('home_team_name')->nullable();
            $table->integer('home_score');
            $table->foreignId('away_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->string('away_team_name')->nullable();
            $table->integer('away_score');
            $table->string('tournament');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historical_games');
    }
};
