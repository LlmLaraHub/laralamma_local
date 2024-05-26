<?php

namespace Tests\Feature;

use App\Services\LlmServices\OllamaClient;
use App\Services\LlmServices\Requests\MessageInDto;
use App\Services\LlmServices\Responses\CompletionResponse;
use Illuminate\Support\Facades\Http;
use Laravel\Pennant\Feature;
use Tests\TestCase;

class OllamaClientTest extends TestCase
{
    //setup
    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
    }

    public function test_completion(): void
    {
        $client = new OllamaClient();

        $data = get_fixture('ollama_results.json');

        Http::fake([
            'localhost:11434/*' => Http::response($data, 200),
        ]);

        $results = $client->completion('test');

        $this->assertInstanceOf(CompletionResponse::class, $results);

    }

    public function test_not_async()
    {
        $client = new OllamaClient();
        $this->assertFalse($client->isAsync());
    }

    public function test_chat(): void
    {
        $client = new OllamaClient();

        $data = get_fixture('ollama_chat_results.json');

        Http::fake([
            'localhost:11434/*' => Http::response($data, 200),
        ]);

        $results = $client->chat([
            MessageInDto::from([
                'content' => 'test',
                'role' => 'system',
            ]),
            MessageInDto::from([
                'content' => 'test',
                'role' => 'user',
            ]),
        ]);

        $this->assertInstanceOf(CompletionResponse::class, $results);

        Http::assertSent(function ($request) {
            $message1 = $request->data()['messages'];

            return count($message1) === 2;
        });

    }

    public function test_functions_prompt(): void
    {
        if (! Feature::active('ollama-functions')) {
            $this->markTestSkipped('Feature ollama-functions is not active');
        }
        $data = get_fixture('ollamas_function_response.json');

        Http::fake([
            'localhost:11434/*' => Http::response($data, 200),
        ]);

        $openaiClient = new OllamaClient();
        $response = $openaiClient->functionPromptChat([
            MessageInDto::from([
                'content' => 'test',
                'role' => 'system',
            ]),
            MessageInDto::from([
                'content' => 'test',
                'role' => 'user',
            ]),
        ]);

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
    }
}
