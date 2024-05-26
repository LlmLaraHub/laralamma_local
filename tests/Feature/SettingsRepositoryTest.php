<?php

namespace Tests\Feature;

use App\Domains\Settings\SettingsRepository;
use App\Models\Setting;
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

    public function test_add_model(): void
    {
        Setting::factory()->create([
            'models' => [],
        ]);

        $this->assertFalse(
            (new SettingsRepository())->hasModel(
                'llama3'
            )
        );

        $setting = (new SettingsRepository())->addModel(
            'llama3'
        );

        $this->assertNotNull(
            data_get($setting->refresh()->models, 'chat.0')
        );

        $this->assertEquals(
            'llama3',
            data_get($setting->refresh()->models, 'chat.0')
        );

        $this->assertTrue(
            (new SettingsRepository())->hasModel(
                'llama3'
            )
        );
    }
}
