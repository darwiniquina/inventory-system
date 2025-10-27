<?php

namespace Database\Seeders;

use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Enums\StockMovementEnum;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);

       $now = Carbon::now();

        // --- Categories ---
        $categories = [
            ['name' => 'Electronics', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Furniture', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Office Supplies', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lighting', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Accessories', 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('categories')->insert($categories);

        // --- Suppliers ---
        $suppliers = [
            [
                'name' => 'BrightTech Distributors',
                'contact_person' => 'Jane Torres',
                'email' => 'sales@brighttech.com',
                'phone' => '+1-555-210-4455',
                'address' => '88 Market St',
                'city' => 'San Francisco',
                'country' => 'USA',
                'notes' => 'Primary supplier for electronics and accessories.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'FurniWorks Ltd.',
                'contact_person' => 'Marcus Lee',
                'email' => 'contact@furniworks.co',
                'phone' => '+1-555-381-1199',
                'address' => '102 Industrial Ave',
                'city' => 'Dallas',
                'country' => 'USA',
                'notes' => 'Ships weekly; net 30 payment terms.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'OfficeBase',
                'contact_person' => 'Nora Patel',
                'email' => 'nora@officebase.com',
                'phone' => '+1-555-500-8822',
                'address' => '12 Harbor Rd',
                'city' => 'Chicago',
                'country' => 'USA',
                'notes' => 'Specializes in stationery and office supplies.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('suppliers')->insert($suppliers);

        // --- Products ---
        $products = [
            [
                'name' => 'USB-C Cable',
                'category_id' => 1,
                'supplier_id' => 1,
                'sku' => 'UC-002',
                'cost' => 3.50,
                'price' => 8.99,
                'stock' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Desk Lamp',
                'category_id' => 4,
                'supplier_id' => 2,
                'sku' => 'DL-084',
                'cost' => 12.00,
                'price' => 29.99,
                'stock' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Office Chair',
                'category_id' => 2,
                'supplier_id' => 2,
                'sku' => 'OC-551',
                'cost' => 45.00,
                'price' => 99.99,
                'stock' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Notebook Pack',
                'category_id' => 3,
                'supplier_id' => 3,
                'sku' => 'NP-010',
                'cost' => 2.50,
                'price' => 6.50,
                'stock' => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('products')->insert($products);

        $user = User::first() ?? User::factory()->create(['name' => 'Admin User']);

        $movements = [
            [
                'product_id' => 1,
                'type' => StockMovementEnum::IN,
                'quantity' => 20,
                'reason' => 'Initial stock load',
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 2,
                'type' => StockMovementEnum::OUT,
                'quantity' => 3,
                'reason' => 'Customer order',
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 3,
                'type' => StockMovementEnum::IN,
                'quantity' => 10,
                'reason' => 'Restock delivery',
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('stock_movements')->insert($movements);
    }
}
