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
   protected function getTableHeading(): string
    {
        return 'Low Stock Alert';
    }

    protected function getTableDescription(): ?string
    {
        return 'Products that need restocking when stock falls below the warning level.';    
    }

    protected function getTableQuery(): Builder
    {
        return Product::query()->whereRaw('stock < stock_warning_level');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Product'),
            TextColumn::make('sku')->label('SKU'),
            TextColumn::make('category.name')
                ->badge()
                ->label('Category'),
            TextColumn::make('stock')
                ->label('Stock')
                ->badge()
                ->color('danger'),
            TextColumn::make('stock_warning_level')     
                ->label('Stock Warning Level')
                ->badge()
                ->color('danger'),  
        ];
    }
}
