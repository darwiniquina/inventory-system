<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Tables\Table;
use App\Enums\StockMovementEnum;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SelectColumn::make('product_id')
                    ->label('Product')
                    ->optionsRelationship(name: 'product', titleAttribute: 'name')
                    ->rules(['required']),
                SelectColumn::make('type') 
                    ->options([
                        StockMovementEnum::IN => 'Stock (In)',
                        StockMovementEnum::OUT => 'Stock (Remove)',
                        StockMovementEnum::ADJUSTMENT => 'Adjustment',
                    ])
                    ->searchable(),
                TextInputColumn::make('quantity')
                    ->sortable(),
                TextInputColumn::make('reason')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
