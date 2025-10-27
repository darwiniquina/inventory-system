<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make('name')->searchable(),
                TextInputColumn::make('contact_person')->searchable(),
                TextInputColumn::make('email')->searchable(),
                TextInputColumn::make('phone')->searchable(),
                TextInputColumn::make('address')->searchable(),
                TextInputColumn::make('city')->searchable(),
                TextInputColumn::make('country')->searchable(),
                TextInputColumn::make('notes'),
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
