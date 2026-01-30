<?php

namespace Database\Factories;

use app\Domains\Loyalty\Enums\AchievementType;
use App\Domains\Loyalty\Persistence\Entities\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class AchievementFactory extends Factory
{
    protected $model = Achievement::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'type' => $this->faker->randomElement(AchievementType::values()),
            'threshold' => $this->faker->numberBetween(1, 10),
            'reward' => $this->faker->numberBetween(10, 1_000),
            'description' => $this->faker->text(),
        ];
    }
}
