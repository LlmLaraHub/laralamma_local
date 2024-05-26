<?php

namespace App\Domains\Settings;

class DownloadOllama
{

    public function downloadPath(string $package_name) : string
    {
        return \Illuminate\Support\Facades\Storage::disk('downloads')->path($package_name);
    }
    public function isDownloaded(string $package_name) : bool
    {
        return \Illuminate\Support\Facades\Storage::disk('downloads')->exists($package_name);
    }
}
