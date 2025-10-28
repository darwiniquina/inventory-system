<?php

namespace App\Filament\Resources\StockMovements\Tables;

use App\Enums\StockMovementEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->searchable(),
                TextColumn::make('product.sku')->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (Model $record): string => match ($record->type) {
                        StockMovementEnum::IN => 'success',
                        StockMovementEnum::OUT => 'danger',
                        StockMovementEnum::ADJUSTMENT_IN => 'warning',
                        StockMovementEnum::ADJUSTMENT_OUT => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => Str::of($state)
                        ->replace('_', ' ')
                        ->title()
                    ),
                TextColumn::make('quantity')->badge()->sortable(),
                TextColumn::make('reason'),
                TextColumn::make('user.name'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
