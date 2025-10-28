<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Enums\StockMovementEnum;
use App\Models\Product;
use App\Rules\EnoughStock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->required()
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->loadingMessage('Loading products...')
                    ->live(),

                Grid::make()->columnSpan(1)->columns(2)->schema([
                    TextEntry::make('available_stock')
                        ->label('Available Stock Quantity')
                        ->badge()
                        ->size(TextSize::Large)
                        ->icon(fn (Get $get) => self::getStockPreview($get)['stock_icon'])
                        ->color(fn (Get $get) => self::getStockPreview($get)['color'])
                        ->state(fn (Get $get) => self::getStockPreview($get)['stock'])
                        ->visible(fn (Get $get): bool => (bool) $get('product_id')),

                    TextEntry::make('stock_warning_level')
                        ->label('Stock Warning Level')
                        ->badge()
                        ->size(TextSize::Large)
                        ->icon(fn (Get $get) => self::getStockPreview($get)['warning_level_icon'])
                        ->color(fn (Get $get) => self::getStockPreview($get)['color'])
                        ->state(fn (Get $get) => self::getStockPreview($get)['warning_level'])
                        ->visible(fn (Get $get): bool => filled($get('product_id'))),
                ]),

                Select::make('type')
                    ->required()
                    ->options([
                        StockMovementEnum::IN => 'Stock (In)',
                        StockMovementEnum::OUT => 'Stock (Remove)',
                        StockMovementEnum::ADJUSTMENT_IN => 'Adjustment (In)',
                        StockMovementEnum::ADJUSTMENT_OUT => 'Adjustment (Remove)',
                    ])
                    ->reactive(),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->rules([fn (Get $get) => new EnoughStock($get('product_id'), $get('type'))])
                    ->reactive()
                    ->minValue(1),

                TextEntry::make('stock_preview')
                    ->label('Stock After Movement')
                    ->badge()
                    ->size(TextSize::Large)
                    ->icon(fn (Get $get) => self::getPredictedPreview($get)['icon'])
                    ->color(fn (Get $get) => self::getPredictedPreview($get)['color'])
                    ->state(fn (Get $get) => self::getPredictedPreview($get)['predicted'])
                    ->visible(fn (Get $get): bool => filled($get('product_id')) && filled($get('quantity')) && filled($get('type'))),

                Textarea::make('reason'),
            ])
            ->columns(1);
    }

    protected static function getProduct(Get $get): ?Product
    {
        static $cache = [];

        $productId = $get('product_id');
        if (! $productId) {
            return null;
        }

        if (! isset($cache[$productId])) {
            $cache[$productId] = Product::find($productId);
        }

        return $cache[$productId];
    }

    protected static function getStockPreview(Get $get): array
    {
        $product = self::getProduct($get);
        $stock = $product->stock ?? 0;
        $warning = $product->stock_warning_level ?? 0;

        if ($stock <= 0) {
            return [
                'stock_icon' => Heroicon::ArchiveBoxXMark,
                'warning_level_icon' => Heroicon::XCircle,
                'color' => 'danger',
                'stock' => "{$stock} — This item is currently out of stock.",
                'warning_level' => $warning,
            ];
        }

        if ($stock <= $warning) {
            return [
                'stock_icon' => Heroicon::CubeTransparent,
                'warning_level_icon' => Heroicon::ExclamationTriangle,
                'color' => 'warning',
                'stock' => "{$stock} — Stock is approaching the warning level.",
                'warning_level' => $warning,
            ];
        }

        return [
            'stock_icon' => Heroicon::Cube,
            'warning_level_icon' => Heroicon::CheckCircle,
            'color' => 'success',
            'stock' => "{$stock} — Stock level is healthy.",
            'warning_level' => $warning,
        ];
    }

    protected static function getPredictedPreview(Get $get): array
    {
        $product = self::getProduct($get);
        if (! $product) {
            return [
                'predicted' => '— Product not found.',
                'color' => 'gray',
                'icon' => Heroicon::QuestionMarkCircle,
            ];
        }

        $quantity = (int) $get('quantity');
        $type = $get('type');
        $current = $product->stock ?? 0;
        $warning = $product->stock_warning_level ?? 0;

        $predicted = match ($type) {
            StockMovementEnum::IN, StockMovementEnum::ADJUSTMENT_IN => $current + $quantity,
            StockMovementEnum::OUT, StockMovementEnum::ADJUSTMENT_OUT => $current - $quantity,
            default => $current,
        };

        if ($predicted < 0) {
            return [
                'predicted' => "{$predicted} — Not enough stock to complete this movement.",
                'color' => 'danger',
                'icon' => Heroicon::XCircle,
            ];
        }

        if ($predicted <= $warning) {
            return [
                'predicted' => "{$predicted} — Stock will fall below the warning level.",
                'color' => 'warning',
                'icon' => Heroicon::ExclamationTriangle,
            ];
        }

        return [
            'predicted' => "{$predicted} — Stock level remains healthy.",
            'color' => 'success',
            'icon' => Heroicon::CheckCircle,
        ];
    }
}
