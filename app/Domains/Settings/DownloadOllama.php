<?php

namespace App\Domains\Settings;

use Illuminate\Support\Facades\Log;

class DownloadOllama
{
    public function downloadPath(string $package_name): string
    {
        return \Illuminate\Support\Facades\Storage::disk('downloads')->path($package_name);
    }

    public function isDownloaded(string $package_name): bool
    {
        return \Illuminate\Support\Facades\Storage::disk('downloads')->exists($package_name);
    }

    public function download(string $package_name = 'Ollama-darwin.zip'): bool|string
    {
        try {
            $url = 'https://ollama.com/download/';
            $response = \Illuminate\Support\Facades\Http::get($url.$package_name);
            if ($response->ok()) {
                \Illuminate\Support\Facades\Storage::disk('downloads')->put($package_name, $response->body());

                return true;
            } else {
                return $response->body();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function pullModel(string $model = 'llama3'): bool|string
    {
        Log::info('Starting pullModel of '.$model);
        try {
            $process = \Illuminate\Support\Facades\Process::timeout(600)->start('ollama pull '.$model);

            while ($process->running()) {
                Log::info('Running pullModel of '.$model);
            }

            $results = $process->wait();

            if ($results->successful()) {
                Log::info('Completed pullModel of '.$model);

                return true;
            } else {

                Log::info('Failed pullModel of '.$model);

                return $results->errorOutput();
            }
        } catch (\Exception $e) {
            Log::info('Failed pullModel of '.$model);
            Log::error('An error occurred getting model : '.$e->getMessage());

            return $e->getMessage();
        }
    }
}
