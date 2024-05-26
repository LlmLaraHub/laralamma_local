<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property bool $ollama_server_reachable
 * @property bool $ollama_downloaded
 */
class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'ollama_downloaded' => 'bool',
        'ollama_binary_downloaded' => 'bool',
        'ollama_server_reachable' => 'bool',
    ];
}
