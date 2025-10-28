<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Filament\Exports\ProductExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Concerns\InteractsWithTable;

class ProductsTable
{
    use InteractsWithTable;

    public static function configure(Table $table): Table
    {
        $showSummaries = false;
        return $table
            ->columns([
                TextInputColumn::make('name')
                    ->searchable(isIndividual: true)
                    ->rules(['required']),

                TextInputColumn::make('sku')
                    ->label('SKU')
                    ->searchable(isIndividual: true)
                    ->rules(['required']),

                SelectColumn::make('category_id')
                    ->label('Category')
                    ->optionsRelationship('category', 'name'),

                SelectColumn::make('supplier_id')
                    ->label('Supplier')
                    ->optionsRelationship('supplier', 'name'),

                TextInputColumn::make('cost')
                    ->rules(['required'])
                    ->summarize([
                        Sum::make()->label('Sum. Cost')->hidden(fn (Table $table) => ! $table->getLivewire()->showSummaries),
                        Average::make()->label('Avg. Cost')->hidden(fn (Table $table) => ! $table->getLivewire()->showSummaries)
                    ]),

                TextInputColumn::make('price')
                    ->rules(['required'])
                    ->summarize([
                        Sum::make()->label('Sum. Price')->hidden(fn (Table $table) => ! $table->getLivewire()->showSummaries),
                        Average::make()->label('Avg. Price')->hidden(fn (Table $table) => ! $table->getLivewire()->showSummaries)
                    ]),

                TextColumn::make('stock')
                    ->badge()
                    ->summarize([
                        Sum::make()->label('Sum. Stock')->hidden(fn (Table $table) => ! $table->getLivewire()->showSummaries),
                        Average::make()->label('Avg. Stock')->hidden(fn (Table $table) => ! $table->getLivewire()->showSummaries)
                    ])
                    ->color(fn (Model $record): string => match (true) {
                        $record->stock <= 0 => 'danger',
                        $record->stock <= $record->stock_warning_level => 'warning',
                        default => 'success',
                    })
                    ->tooltip(fn (Model $record): ?string => match (true) {
                        $record->stock <= 0 => "{$record->stock} — Out of stock",
                        $record->stock <= $record->stock_warning_level => "{$record->stock} — Low stock",
                        default => "{$record->stock} — Healthy",
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
            ->searchPlaceholder('Search (Name, SKU)')
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->reorderableColumns()
            ->persistFiltersInSession()
            ->groupingSettingsInDropdownOnDesktop()
            ->groups([
                Group::make('category.name')->collapsible()->titlePrefixedWithLabel(false),
                Group::make('supplier.name')->collapsible()->titlePrefixedWithLabel(false),
            ])
            ->filters([
                Filter::make('stock_level')
                    ->schema([
                        Select::make('stock_level')->options([
                            'out_of_stock' => 'Out of stock',
                            'low_stock' => 'Low stock',
                            'healthy' => 'Healthy',
                        ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['stock_level'] === 'out_of_stock', fn ($q) => $q->where('stock', '<=', 0))
                            ->when($data['stock_level'] === 'low_stock', fn ($q) => $q->whereRaw('stock <= stock_warning_level'))
                            ->when($data['stock_level'] === 'healthy', fn ($q) => $q->whereRaw('stock > stock_warning_level'));
                    }),

                SelectFilter::make('category')
                    ->label('Category')
                    ->multiple()
                    ->relationship('category', 'name'),

                SelectFilter::make('supplier')
                    ->label('Supplier')
                    ->multiple()
                    ->relationship('supplier', 'name'),

                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('To'),
                    ])
                    ->columns(2)
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    ),
                ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),

                    ReplicateAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Replicate Product')
                        ->modalDescription('Creates a duplicate product with zero stock.')
                        ->modalSubmitActionLabel('Replicate product')
                        ->beforeReplicaSaved(fn (Model $replica) => [
                            $replica->name = $replica->name . ' (copy)',
                            $replica->sku = $replica->sku . '-copy',
                            $replica->stock = 0,
                        ]),

                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Product')
                        ->modalDescription('This action cannot be undone.')
                        ->modalSubmitActionLabel('Delete Product'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('Export')->exporter(ProductExporter::class),
                ]),
            ])
            ->headerActions([
                Action::make('toggleSummaries')
                    ->label('Summary')
                    ->icon(fn (Table $table): string =>
                        $table->getLivewire()->showSummaries
                            ? 'heroicon-o-eye-slash'
                            : 'heroicon-o-eye'
                    )
                    ->button()
                    ->action(function (Table $table): void {
                        $livewire = $table->getLivewire();
                        $newValue = ! $livewire->showSummaries;

                        $livewire->showSummaries = $newValue;
                        session(['showSummaries' => $newValue]);
                    }),

                CreateAction::make()
                ->label('New')
                 ->icon(Heroicon::OutlinedPlus),
            ]);
    }
}
