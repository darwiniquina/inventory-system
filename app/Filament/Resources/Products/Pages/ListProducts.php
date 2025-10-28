<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;
    public bool $showSummaries = false;

    
    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public function mount(): void
    {
        parent::mount();
        $this->showSummaries = session('showSummaries', false);
    }
}
