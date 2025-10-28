<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('sku')
                    ->label('SKU')
                    ->unique()
                    ->required(),

                Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->nullable(),

                Select::make('supplier_id')
                    ->relationship(name: 'supplier', titleAttribute: 'name')
                    ->nullable(),

                Grid::make()->columnSpan(1)->columns(2)->schema([
                    TextInput::make('cost')
                        ->required()
                        ->numeric()
                        ->prefix('₱'),

                    TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->prefix('₱'),
                ]),

                TextInput::make('stock_warning_level')
                    ->label('Stock Warning Level')
                    ->hint('The system will alert you when the product quantity falls below this value (default: 10).')
                    ->numeric()
                    ->default(10),

                TextInput::make('stock')
                    ->label('Current Stock')
                    ->hint('This value is managed automatically through stock movements and cannot be edited directly.')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ])->columns(1);
    }
}
