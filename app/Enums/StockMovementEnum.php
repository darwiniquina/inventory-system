<?php

namespace App\Enums;

enum StockMovementEnum
{
    public const ALL = [
        self::IN,
        self::OUT,
        self::ADJUSTMENT_IN,
        self::ADJUSTMENT_OUT,
    ];

    public const IN = 'in';

    public const OUT = 'out';

    public const ADJUSTMENT_IN = 'adjustment_in';

    public const ADJUSTMENT_OUT = 'adjustment_out';
}
