<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Schemas\Schema;
use App\Enums\StockMovementEnum;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        $user_id = Auth::id();
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship(name: 'product', titleAttribute: 'name'),

                Select::make('type')   
                    ->options([
                        StockMovementEnum::IN => 'Stock (In)',
                        StockMovementEnum::OUT => 'Stock (Remove)',
                        StockMovementEnum::ADJUSTMENT => 'Adjustment',
                    ]),

                TextInput::make('quantity')
                    ->required()
                    ->numeric(),

                TextInput::make('reason'),
            ])
            ->columns(1);
    }
}
