<?php

namespace App\Domains\Settings;

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

    public function download(string $package_name = 'Ollama-darwin.zip') : bool | string
    {
        try {
            $url = 'https://ollama.com/download/';
            $response = \Illuminate\Support\Facades\Http::get($url . $package_name);
            if($response->ok()) {
                \Illuminate\Support\Facades\Storage::disk('downloads')->put($package_name, $response->body());
                return true;
            } else {
                return $response->body();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function pullModel(string $model = 'llama3') : bool | string
    {
        try {
            $results = \Illuminate\Support\Facades\Process::run('ollama pull llama3');
            if($results->successful()) {
                return true;
            } else {
                $results->throw();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("An error occurred getting model : " . $e->getMessage());
            return $e->getMessage();
        }
    }
}
