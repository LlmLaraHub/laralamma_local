<?php

namespace Database\Factories;

use App\Domains\Llms\PullStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Llm>
 */
class LlmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_name' => fake()->randomElement([
                'llama3',
                'gemma ',
            ]),
            'last_run' => fake()->dateTime(),
            'status' => PullStatus::Pending,
        ];
    }
}
