<?php

namespace Tests\Feature;

use App\Services\LlmServices\LlmDriverFacade;
use App\Services\LlmServices\Responses\CompletionResponse;
use Tests\TestCase;

class LlmDriverFacadeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_facade(): void
    {
        $results = LlmDriverFacade::driver('mock')->completion('test');

        $this->assertInstanceOf(
            CompletionResponse::class,
            $results
        );
    }
}
