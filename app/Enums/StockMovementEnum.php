<?php

namespace App\Enums;

enum StockMovementEnum {
    public const ALL = [
        self::IN,
        self::OUT,
        self::ADJUSTMENT,   
    ];

    public const IN = 'in';
    public const OUT = 'out';
    public const ADJUSTMENT = 'adjustment';
}
