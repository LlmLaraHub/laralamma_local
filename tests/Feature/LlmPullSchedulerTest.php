<?php

namespace Tests\Feature;

use App\Domains\Llms\LlmPullScheduler;
use App\Domains\Llms\PullStatus;
use App\Models\Llm;
use Facades\App\Domains\Settings\DownloadOllama;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LlmPullSchedulerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_scheduler(): void
    {
        Llm::factory()->create([
            'status' => PullStatus::Pending
        ]);
        DownloadOllama::shouldReceive("pullModel")->once()->andReturn(true);

        (new LlmPullScheduler())->process();
    }
}
