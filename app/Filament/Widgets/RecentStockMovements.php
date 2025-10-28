<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\StockMovement;
use App\Enums\StockMovementEnum;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentStockMovements extends BaseWidget
{
    protected static ?string $heading = 'Recent Stock Movements';

    protected function getTableQuery(): Builder
    {
        return StockMovement::query()
            ->latest()
            ->limit(5)
            ->with(['product', 'user']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('product.name')->label('Product'),
            TextColumn::make('type')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    StockMovementEnum::IN => 'success',
                    StockMovementEnum::OUT => 'danger',
                    StockMovementEnum::ADJUSTMENT_IN => 'warning',
                    StockMovementEnum::ADJUSTMENT_OUT => 'warning',
                    default => 'gray',
                }),
            TextColumn::make('quantity')->badge(),
            TextColumn::make('user.name')->label('By'),
            TextColumn::make('created_at')->since(),
        ];
    }
}
