<?php

namespace App\Models;

use App\Models\BaseModel;

class Campaign extends BaseModel
{


// status  ['open', 'paused', 'completed', 'cancelled'] = number

    public const STATUS_OPEN = 1;
    public const STATUS_PAUSED = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_CANCELLED = 4;

    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_PAUSED => 'Paused',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
