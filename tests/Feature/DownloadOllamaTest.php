<?php

namespace Tests\Feature;

use App\Domains\Settings\DownloadOllama;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadOllamaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Storage::fake('downloads');
        Process::preventStrayProcesses();
    }

    /**
     * A basic feature test example.
     */
    public function test_download_ollama(): void
    {
        Http::fake([
            'ollama.com/*' => Http::response('foo'),
        ]);

        $results = (new DownloadOllama())->download();

        $this->assertTrue($results);
    }

    public function test_download_ollama_failed(): void
    {
        Http::fake([
            'ollama.com/*' => Http::response('foo', 500),
        ]);

        $results = (new DownloadOllama())->download();

        $this->assertStringContainsString('foo', $results);
    }

    public function test_pull()
    {
        Process::fake();
        (new DownloadOllama())->pullModel();

        Process::assertRan('ollama pull llama3');
    }

    public function test_pull_error()
    {
        Process::fake([
            '*' => Process::result(
                output: 'Test output',
                errorOutput: 'Test error output',
                exitCode: 1,
            ),
        ]);
        $results = (new DownloadOllama())->pullModel();
        $this->assertStringContainsString('Test error output', $results);
    }
}
