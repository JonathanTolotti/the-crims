<?php

namespace App\Filament\Resources\VipTierResource\Pages;

use App\Filament\Resources\VipTierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVipTier extends EditRecord
{
    protected static string $resource = VipTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
