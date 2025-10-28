<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Exports\ProductExporter;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                    ->searchable()
                    ->label('SKU')
                    ->rules(['required']),

                SelectColumn::make('category_id')
                    ->searchable()
                    ->label('Category')
                    ->optionsRelationship(name: 'category', titleAttribute: 'name'),

                SelectColumn::make('supplier_id')
                    ->searchable()
                    ->label('Supplier')
                    ->optionsRelationship(name: 'supplier', titleAttribute: 'name'),

                TextInputColumn::make('cost')->rules(['required']),

                TextInputColumn::make('price')->rules(['required']),

                TextColumn::make('stock')
                    ->badge()
                    ->color(function (Model $record): string {
                        if ($record->stock <= 0) {
                            return 'danger';
                        }

                        if ($record->stock <= $record->stock_warning_level) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->tooltip(function (Model $record): ?string {
                        if ($record->stock <= 0) {
                            return "{$record->stock} — Out of stock";
                        }

                        if ($record->stock <= $record->stock_warning_level) {
                            return "{$record->stock} — Low stock";
                        }

                        return "{$record->stock} — Healthy";
                    })
                    ->sortable(),

                TextInputColumn::make('stock_warning_level')->sortable(),

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
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),

                    ReplicateAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Replicate Product')
                        ->modalDescription('This will create a duplicate of the product with zero stock, You can edit it later if needed.')
                        ->modalSubmitActionLabel('Replicate product')
                        ->beforeReplicaSaved(function (Model $replica): void {
                            $replica->name = $replica->name.' (copy)';
                            $replica->sku = $replica->sku.'-copy';
                            $replica->stock = 0;
                        }),

                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Product')
                        ->modalDescription('Are you sure you want to delete this Product? This action cannot be undone and all related to products will also be removed.')
                        ->modalSubmitActionLabel('Delete Product'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('Export')->exporter(ProductExporter::class),
                ]),
            ]);
    }
}
