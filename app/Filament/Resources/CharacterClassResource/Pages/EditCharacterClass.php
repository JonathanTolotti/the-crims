<?php

namespace App\Filament\Resources\CharacterClassResource\Pages;

use App\Filament\Resources\CharacterClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCharacterClass extends EditRecord
{
    protected static string $resource = CharacterClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
