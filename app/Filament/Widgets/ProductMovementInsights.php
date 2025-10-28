<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Product;
use Filament\Widgets\Widget;
use App\Models\StockMovement;
use App\Enums\StockMovementEnum;
use Illuminate\Support\Facades\DB;

class ProductMovementInsights extends Widget
{
    protected string $view = 'filament.widgets.product-movement-insights';
    
    
    public function getData(): array
    {
        $days = 30;

        $fastMovers = StockMovement::select('product_id', DB::raw('SUM(quantity) as total_out'))
            ->where('type', StockMovementEnum::OUT)
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('product_id')
            ->orderByDesc('total_out')
            ->with('product')
            ->limit(5)
            ->get();

        $slowMovers = Product::whereDoesntHave('movements', function ($q) use ($days) {
            $q->where('created_at', '>=', Carbon::now()->subDays($days));
        })
        ->limit(5)
        ->get();

        return [
            'fastMovers' => $fastMovers,
            'slowMovers' => $slowMovers,
            'days' => $days,
        ];
    }
}
