<?php
namespace App\Filament\Resources;

use App\Enums\RefineFailureOutcomeEnum;
use App\Filament\Resources\TierUpgradeRuleResource\Pages;
use App\Models\TierUpgradeRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TierUpgradeRuleResource extends Resource
{
    protected static ?string $model = TierUpgradeRule::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationGroup = 'Regras do Jogo';

    protected static ?string $modelLabel = 'Regra de Refinamento';
    protected static ?string $pluralModelLabel = 'Regras de Refinamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('from_tier')->numeric()->required(),
                Forms\Components\TextInput::make('to_tier')->numeric()->required(),
                Forms\Components\Select::make('required_item_id')
                    ->relationship('requiredItem', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('required_item_quantity')->numeric()->required()->default(1),
                Forms\Components\TextInput::make('required_money')->numeric()->required()->default(0),
                Forms\Components\TextInput::make('success_chance')->numeric()->required()->minValue(0)->maxValue(100),
                Forms\Components\Select::make('failure_outcome')
                    ->options(RefineFailureOutcomeEnum::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_tier')->label('Do Tier')->sortable(),
                Tables\Columns\TextColumn::make('to_tier')->label('Para o Tier'),
                Tables\Columns\TextColumn::make('requiredItem.name')->label('Item Necessário'),
                Tables\Columns\TextColumn::make('required_money')->money('BRL'),
                Tables\Columns\TextColumn::make('success_chance')->suffix('%')->sortable(),
                Tables\Columns\TextColumn::make('failure_outcome')->badge(),
            ])
            ->defaultSort('from_tier')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTierUpgradeRules::route('/'),
            'create' => Pages\CreateTierUpgradeRule::route('/create'),
            'edit' => Pages\EditTierUpgradeRule::route('/{record}/edit'),
        ];
    }
}
