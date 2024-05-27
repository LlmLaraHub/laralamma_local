<?php

namespace Tests\Feature\Models;

use App\Domains\Llms\PullStatus;
use App\Models\Llm;
use Tests\TestCase;

class LlmTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_model(): void
    {
        $model = Llm::factory()->create();
        $this->assertInstanceOf(
            PullStatus::class,
            $model->status
        );
    }
}
