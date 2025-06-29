<?php

namespace App\Filament\Resources\StoreProductResource\Pages;

use App\Filament\Resources\StoreProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreProduct extends EditRecord
{
    protected static string $resource = StoreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
