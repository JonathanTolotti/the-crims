<?php

namespace App\Filament\Resources\TierUpgradeRuleResource\Pages;

use App\Filament\Resources\TierUpgradeRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTierUpgradeRule extends EditRecord
{
    protected static string $resource = TierUpgradeRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
