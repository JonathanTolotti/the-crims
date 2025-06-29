<?php

namespace App\Filament\Resources\StoreProductResource\Pages;

use App\Filament\Resources\StoreProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoreProducts extends ListRecords
{
    protected static string $resource = StoreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
