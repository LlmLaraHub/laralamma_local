<?php

namespace Tests\Feature;

use App\Domains\Settings\SettingsRepository;
use Tests\TestCase;

class SettingsRepositoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_setup(): void
    {
        $this->assertDatabaseCount('settings', 0);
        (new SettingsRepository())->getSetting();
        $this->assertDatabaseCount('settings', 1);
    }

    public function test_update_setting(): void
    {
        $setting = (new SettingsRepository())->updateSetting(
            'ollama_downloaded',
            true
        );

        $this->assertEquals(true, $setting->ollama_downloaded);
    }
}
