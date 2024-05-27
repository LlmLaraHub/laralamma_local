<?php

namespace App\Domains\Llms;

use App\Helpers\EnumHelperTrait;

/**
 * @property PullStatus $status
 */
enum PullStatus: string
{
    use EnumHelperTrait;

    case Pending = 'pending';
    case Processing = 'processing';

    case Complete = 'complete';

    case Failed = 'failed';
}
