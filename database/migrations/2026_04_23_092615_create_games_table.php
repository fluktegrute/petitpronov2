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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('api_id');
            $table->dateTime('kickoff_at');
            $table->string('status')->default('scheduled');
            $table->string('stage')->nullable();
            $table->string('group')->nullable();
            $table->integer('bracket_slot')->nullable();
            $table->integer('matchday')->nullable();
            $table->foreignId('home_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('away_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->decimal('odds_home', 5, 2)->nullable();
            $table->decimal('odds_draw', 5, 2)->nullable();
            $table->decimal('odds_away', 5, 2)->nullable();
            $table->foreignId('winner_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
