<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;

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
               
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
            ])->columns(1);
    }
}
