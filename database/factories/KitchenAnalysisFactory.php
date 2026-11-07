<?php

namespace Database\Factories;

use App\Models\KitchenAnalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KitchenAnalysis>
 */
class KitchenAnalysisFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'photo_disk' => 'local',
            'photo_path' => 'kitchen-analyses/'.fake()->uuid().'.jpg',
            'photo_mime_type' => 'image/jpeg',
            'cabinet_count' => fake()->numberBetween(4, 20),
            'drawer_count' => fake()->numberBetween(0, 10),
            'estimated_wrappable_surface_m2' => fake()->randomFloat(2, 3, 25),
            'description' => fake()->paragraph(),
            'provider' => 'openai',
            'model' => 'gpt-test',
        ];
    }
}
