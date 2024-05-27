<?php

namespace App\Models;

use App\Domains\Llms\PullStatus;
use App\Domains\Llms\TypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon $last_run
 * @property PullStatus $status
 */
class Llm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => PullStatus::class,
        'type' => TypeEnum::class,
        'last_run' => "datetime"
    ];
}
