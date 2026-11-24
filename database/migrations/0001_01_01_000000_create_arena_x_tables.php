<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table): void {
                $table->id();
                $table->string('name', 120);
                $table->string('email', 190)->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('nickname', 40)->nullable()->unique();
                $table->string('role', 24)->default('player');
                $table->text('avatar_url')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table): void {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table): void {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index()->constrained()->nullOnDelete();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
        if (! Schema::hasTable('games')) {
            Schema::create('games', function (Blueprint $table): void {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('short_name', 30)->nullable();
                $table->text('cover_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table): void {
                $table->id();
                $table->string('name')->unique();
                $table->string('tag', 12)->unique();
                $table->text('description')->nullable();
                $table->text('logo_url')->nullable();
                $table->foreignId('captain_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('team_members')) {
            Schema::create('team_members', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('team_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('member_role', 24)->default('player');
                $table->timestampTz('joined_at')->useCurrent();
                $table->unique(['team_id', 'user_id']);
            });
        }
        if (! Schema::hasTable('tournaments')) {
            Schema::create('tournaments', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('game_id')->constrained()->restrictOnDelete();
                $table->foreignId('organizer_id')->constrained('users')->restrictOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->text('rules')->nullable();
                $table->string('format', 32)->default('single_elimination');
                $table->string('participant_type', 20)->default('team');
                $table->unsignedSmallInteger('team_size')->default(5);
                $table->unsignedInteger('max_participants')->default(16);
                $table->string('prize_pool')->nullable();
                $table->timestampTz('registration_starts_at')->nullable();
                $table->timestampTz('registration_ends_at')->nullable();
                $table->timestampTz('starts_at')->nullable();
                $table->string('status', 24)->default('draft');
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('registrations')) {
            Schema::create('registrations', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
                $table->string('status', 20)->default('pending');
                $table->text('note')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestampTz('reviewed_at')->nullable();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('matches')) {
            Schema::create('matches', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
                $table->unsignedSmallInteger('round_number')->default(1);
                $table->unsignedSmallInteger('match_number')->default(1);
                $table->foreignId('team_a_id')->nullable()->constrained('teams')->nullOnDelete();
                $table->foreignId('team_b_id')->nullable()->constrained('teams')->nullOnDelete();
                $table->timestampTz('scheduled_at')->nullable();
                $table->unsignedSmallInteger('score_a')->nullable();
                $table->unsignedSmallInteger('score_b')->nullable();
                $table->foreignId('winner_team_id')->nullable()->constrained('teams')->nullOnDelete();
                $table->string('status', 20)->default('scheduled');
                $table->string('map_name', 80)->nullable();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->text('body');
                $table->timestampTz('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('tournaments');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('games');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
