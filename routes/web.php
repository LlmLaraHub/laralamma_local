<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'why')->name('home');

Volt::route('/settings', 'settings')->name('settings');

Volt::route('/chat', 'chat')->name('chat');

Volt::route('/documents', 'documents')->name('documents');

Route::get('/ollama/downloaded', function () {
    return response()->download(storage_path('Ollama-darwin.zip'));
})->name('ollama.downloaded');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
