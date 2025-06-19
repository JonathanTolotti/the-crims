<?php

namespace App\Filament\Resources\CrimeLogResource\Pages;

use App\Filament\Resources\CrimeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCrimeLog extends EditRecord
{
    protected static string $resource = CrimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
