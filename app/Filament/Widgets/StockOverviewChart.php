<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class StockOverviewChart extends ChartWidget
{
    protected ?string $heading = 'Stock Overview by Category';

    protected ?string $maxHeight = '17rem';

    protected function getData(): array
    {
        $categories = Category::with('products')->get();

        $labels = $categories->pluck('name');
        $values = $categories->map(fn ($cat) => $cat->products->sum('stock'));

        return [
            'datasets' => [
                [
                    'label' => 'Stock Quantity',
                    'data' => $values,
                    'backgroundColor' => [
                        '#34d399', 
                        '#60a5fa', 
                        '#fbbf24', 
                        '#f87171', 
                        '#a78bfa', 
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
