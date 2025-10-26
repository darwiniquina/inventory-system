<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\StockMovement;

class InventoryDashboard extends Component
{
    public function render()
    {
        $products = Product::all();
        $movements = StockMovement::latest()->take(10)->get();

        return view('livewire.inventory-dashboard', [
            'totalProducts' => $products->count(),
            'totalValue' => $products->sum(fn($p) => $p->stock * $p->cost),
            'lowStockItems' => $products->where('stock', '<', 10)->count(),
            'lowStockList' => $products->where('stock', '<', 10),
            'products' => $products,
            'movements' => $movements,
        ]);
    }
}
