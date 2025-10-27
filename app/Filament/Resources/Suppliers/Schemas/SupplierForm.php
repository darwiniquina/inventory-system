<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('contact_person'),
                TextInput::make('email')->email(),
                TextInput::make('phone'),
                TextInput::make('address'),
                TextInput::make('city'),
                TextInput::make('country'),
                Textarea::make('notes'),
            ]);
    }
}
