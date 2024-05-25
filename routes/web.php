<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'settings');

Route::get('/ollama/downloaded', function () {
    return response()->download(storage_path('Ollama-darwin.zip'));
})->name('ollama.downloaded');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
