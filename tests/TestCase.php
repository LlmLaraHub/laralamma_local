<?php

namespace Tests;

use Facades\App\Domains\Settings\DownloadOllama;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    public function setupDownloads()
    {
        DownloadOllama::shouldReceive('downloadPath')
            ->once()
            ->andReturn('some/folder/this.zip');

        DownloadOllama::shouldReceive('isDownloaded')
            ->once()
            ->andReturn(true);
    }
}
