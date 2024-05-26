<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'why')->name('home');

Volt::route('/settings', 'settings')->name('settings');

Volt::route('/chat', 'chat')->name('chat');

Route::get('/ollama/downloaded', function () {
    return response()->download(storage_path('Ollama-darwin.zip'));
})->name('ollama.downloaded');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
