<?php

namespace Tests\Feature;

use App\Models\Setting;
use Tests\TestCase;

class SettingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_model(): void
    {
        $model = Setting::factory()->create();
        $this->assertNotEmpty($model->models);
    }
}
