<?php

namespace Tests\Feature;

use App\Models\Setting;
use Facades\App\Domains\Settings\CheckOllamaRunning;
use Facades\App\Domains\Settings\DownloadOllama;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_settings(): void
    {
        DownloadOllama::shouldReceive('downloadPath')
            ->once()
            ->andReturn('some/folder/this.zip');

        DownloadOllama::shouldReceive('isDownloaded')
            ->once()
            ->andReturn(true);

        $this->get(route('settings'));

        $this->assertDatabaseCount('settings', 1);
        $settings = Setting::first();

        $this->assertTrue($settings->ollama_downloaded);
    }

    public function test_check_running(): void
    {
        DownloadOllama::shouldReceive('downloadPath')
            ->once()
            ->andReturn('some/folder/this.zip');

        DownloadOllama::shouldReceive('isDownloaded')
            ->once()
            ->andReturn(true);

        CheckOllamaRunning::shouldReceive('isRunning')
            ->once()
            ->andReturn(true);

        $data = get_fixture('tags.json');

        CheckOllamaRunning::shouldReceive('getTags')
            ->once()
            ->andReturn($data);

        Volt::test('settings')
            ->call('check');

        $settings = Setting::first();

        $this->assertTrue($settings->ollama_server_reachable);
    }

    public function test_list_models(): void
    {
        DownloadOllama::shouldReceive('downloadPath')
            ->once()
            ->andReturn('some/folder/this.zip');

        DownloadOllama::shouldReceive('isDownloaded')
            ->once()
            ->andReturn(true);

        $data = get_fixture('tags.json');

        CheckOllamaRunning::shouldReceive('getTags')
            ->once()
            ->andReturn($data);

        Volt::test('settings')
            ->call('listModels');

    }
}
