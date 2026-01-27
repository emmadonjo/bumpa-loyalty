<?php

use App\Domains\Loyalty\Enums\AchievementType;
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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', AchievementType::values())->index();
            $table->integer('threshold');
            $table->decimal('reward', 12)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('icon')->nullable();
            $table->integer('achievements_required');
            $table->timestamps();
        });

        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained();
            $table->timestamp('unlocked_at');
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained();
            $table->timestamp('awarded_at');
        });

        Schema::create('loyalty_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('purchase_count')->default(0);
            $table->decimal('total_spent', 12)->default(0);
            $table->decimal('payout_balance', 12)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('loyalty_trackers');
    }
};
