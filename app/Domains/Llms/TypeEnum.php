<?php

namespace App\Domains\Llms;

use App\Helpers\EnumHelperTrait;

/**
 * @property PullStatus $status
 */
enum TypeEnum: string
{
    use EnumHelperTrait;

    case Embedding = 'embedding';

    case Text = 'text';
}
