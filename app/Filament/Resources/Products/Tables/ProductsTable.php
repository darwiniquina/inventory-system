<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Tables\Grouping\Group;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make('name')
                ->searchable(query: function ($query, $search) {
                    $query->where('products.name', 'ILIKE', "%{$search}%");
                })
                ->rules(['required']),
                    
                TextInputColumn::make('sku')
                    ->label('SKU')
                    ->rules(['required']),

                SelectColumn::make('category_id')
                    ->label('Category')
                    ->optionsRelationship(name: 'category', titleAttribute: 'name'),

                SelectColumn::make('supplier_id')
                    ->label('Supplier')
                    ->optionsRelationship(name: 'supplier', titleAttribute: 'name'),

                TextInputColumn::make('cost')->rules(['required']),

                TextInputColumn::make('price')->rules(['required']),
                    
                TextInputColumn::make('stock'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->striped()
            ->paginatedWhileReordering(false)
            ->reorderableColumns()
            ->groupingSettingsInDropdownOnDesktop()
            ->groups([
                Group::make('category.name')->collapsible()->titlePrefixedWithLabel(false),
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
