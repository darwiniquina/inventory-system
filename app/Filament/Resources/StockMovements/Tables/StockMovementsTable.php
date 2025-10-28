<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Enums\StockMovementEnum;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product Name')->searchable(),
                TextColumn::make('product.sku')->label('Product SKU')->searchable(),
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
            ->filters([
                SelectFilter::make('type')
                    ->label('Movement Type')
                    ->options([
                        StockMovementEnum::IN => 'IN',
                        StockMovementEnum::OUT => 'OUT',
                        StockMovementEnum::ADJUSTMENT_IN => 'Adjustment IN',
                        StockMovementEnum::ADJUSTMENT_OUT => 'Adjustment OUT',
                    ])
                    ->multiple(),

                SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label('Product')
                    ->multiple(),

                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->multiple(),

                Filter::make('created_at')
                    ->label('Date Range')
                    ->schema([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $from) => $q->whereDate('created_at', '>=', $from))
                            ->when($data['until'], fn($q, $until) => $q->whereDate('created_at', '<=', $until));
                    }),

                Filter::make('quantity')
                    ->label('Quantity Range')
                    ->schema([
                        TextInput::make('min')->numeric()->label('Min'),
                        TextInput::make('max')->numeric()->label('Max'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['min'], fn($q, $min) => $q->where('quantity', '>=', $min))
                            ->when($data['max'], fn($q, $max) => $q->where('quantity', '<=', $max));
                    }),
            ])
            ->searchPlaceholder('Search (Name, SKU)')
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
