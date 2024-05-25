<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ollama_completion_model' => 'llama3',
            'ollama_embedding_model' => 'mxbai-embed-large',
            'ollama_server_reachable' => true,
        ];
    }
}
