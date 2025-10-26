<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class InventoryStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $totalValue = Product::sum(DB::raw('cost * stock'));
        $lowStockCount = Product::where('stock', '<', 10)->count();

        return [
            Stat::make('Total Products', $totalProducts)
                ->description('Active SKUs in inventory')
                ->icon('heroicon-o-cube'),

            Stat::make('Total Stock Value', '₱' . number_format($totalValue, 2))
                ->description('Based on cost price')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Low Stock Items', $lowStockCount)
                ->description('Below 10 units')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),
        ];
    }
}