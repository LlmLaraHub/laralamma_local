<?php

namespace App\Domains\Settings;

use App\Models\Setting;

class SettingsRepository
{
    public function getSetting(): Setting
    {
        return Setting::firstOrCreate();
    }

    public function updateSetting(
        string $field, mixed $state
    ): Setting {
        $setting = $this->getSetting();

        $setting->update([
            $field => $state,
        ]);

        return $setting;
    }

    public function addModel(string $model, string $type = 'chat'): Setting
    {
        $models = $this->getSetting()->models;

        $models[$type][] = $model;
        $this->getSetting()->updateQuietly([
            'models' => $models,
        ]);

        return $this->getSetting()->refresh();
    }

    public function hasModel(string $model, string $type = 'chat'): bool
    {
        $models = data_get($this->getSetting()->models, $type, []);

        return in_array($model, $models);
    }

    public function hasModels(string $type = 'chat'): bool
    {
        return ! empty(data_get($this->getSetting()->models, $type, []));
    }
}
