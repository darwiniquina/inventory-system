<?php

namespace App\Filament\Resources\StockMovements\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StockMovements\StockMovementResource;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;
}
