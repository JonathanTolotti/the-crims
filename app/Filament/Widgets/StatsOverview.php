<?php
namespace App\Filament\Widgets;

use App\Models\Crime;
use App\Models\Item;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Jogadores', User::count())
                ->description('Jogadores registrados no jogo')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Total de Itens', Item::count())
                ->description('Itens definidos no sistema')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info'),
            Stat::make('Total de Crimes', Crime::count())
                ->description('Crimes disponíveis para os jogadores')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('warning'),
        ];
    }
}
