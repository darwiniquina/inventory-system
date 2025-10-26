<div class="p-6 space-y-6">

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-4">
        <x-filament::card>
            <div class="text-gray-600">Total Products</div>
            <div class="text-2xl font-bold">{{ $totalProducts }}</div>
        </x-filament::card>

        <x-filament::card>
            <div class="text-gray-600">Total Stock Value</div>
            <div class="text-2xl font-bold">${{ number_format($totalValue, 2) }}</div>
        </x-filament::card>

        <x-filament::card>
            <div class="text-gray-600">Low Stock Items</div>
            <div class="text-2xl font-bold text-red-600">{{ $lowStockItems }}</div>
        </x-filament::card>
    </div>

    {{-- Low Stock Alert --}}
    <x-filament::card>
        <div class="font-semibold mb-2">Low Stock Alert</div>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-gray-500">
                    <th class="text-left py-1">Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lowStockList as $p)
                    <tr class="border-b">
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->sku }}</td>
                        <td>{{ $p->category }}</td>
                        <td class="text-red-600">{{ $p->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>

    {{-- Products Table --}}
    <x-filament::card>
        <div class="font-semibold mb-2">Products</div>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-gray-500">
                    <th class="text-left py-1">Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>Sell Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                    <tr class="border-b">
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->sku }}</td>
                        <td>{{ $p->category }}</td>
                        <td>${{ $p->cost }}</td>
                        <td>${{ $p->price }}</td>
                        <td>{{ $p->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>

    {{-- Stock Movements --}}
    <x-filament::card>
        <div class="font-semibold mb-2">Stock Movements</div>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-gray-500">
                    <th>Date</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $m)
                    <tr class="border-b">
                        <td>{{ $m->created_at->format('M d, Y, h:i A') }}</td>
                        <td>{{ $m->product->name }}</td>
                        <td>{{ ucfirst($m->type) }}</td>
                        <td class="{{ $m->quantity < 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $m->quantity }}
                        </td>
                        <td>{{ $m->reason }}</td>
                        <td>{{ $m->user }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>

</div>
