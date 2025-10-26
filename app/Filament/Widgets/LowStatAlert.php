<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget; 
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class LowStatAlert extends TableWidget
{
   protected int|string|array $columnSpan = 'full';
   
   protected function getTableHeading(): string
    {
        return 'Low Stock Alert';
    }

    protected function getTableDescription(): ?string
    {
        return 'Products that need restocking (below 10 units)';
    }

    protected function getTableQuery(): Builder
    {
        return Product::query()->where('stock', '<', 10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Product'),
            TextColumn::make('sku')->label('SKU'),
            BadgeColumn::make('category.name')
                ->label('Category')
                ->colors(['primary']),
            TextColumn::make('stock')
                ->label('Stock')
                ->badge()
                ->color('danger'),
        ];
    }
}
