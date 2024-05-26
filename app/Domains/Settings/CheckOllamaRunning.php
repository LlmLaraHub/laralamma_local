<?php

namespace App\Domains\Settings;

use Illuminate\Support\Facades\Log;

class CheckOllamaRunning
{
    public function isRunning(): bool
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:11434/api/tags');
            if ($response->ok()) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Could not reach server', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getTags(): array
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://localhost:11434/api/tags');
            if ($response->ok()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Could not reach server', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
