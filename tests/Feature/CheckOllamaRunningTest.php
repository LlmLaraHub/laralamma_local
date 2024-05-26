<?php

namespace Tests\Feature;

use App\Domains\Settings\CheckOllamaRunning;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckOllamaRunningTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_is_running(): void
    {
        Http::fake([
            'localhost:11434/*' => Http::response([]),
        ]);

        $this->assertTrue((new CheckOllamaRunning())->isRunning());
    }

    public function test_handles_exception(): void
    {
        Http::fake([
            'localhost:11434/*' => Http::throw(
                function () {
                    throw new \Exception('Yup');
                }
            ),
        ]);

        $this->assertFalse((new CheckOllamaRunning())->isRunning());
    }

    public function test_tags(): void
    {
        $data = get_fixture('tags.json');

        Http::fake([
            'localhost:11434/*' => Http::response(
                $data
            ),
        ]);

        $this->assertNotEmpty((new CheckOllamaRunning())->getTags());
    }
}
