<?php
namespace App\Filament\Resources;

use App\Enums\CharacterAttributeEnum;
use App\Filament\Resources\CrimeResource\Pages;
use App\Models\Crime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrimeResource extends Resource
{
    protected static ?string $model = Crime::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Configurações do Jogo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('description')->columnSpanFull(),
                Forms\Components\Select::make('required_level_id')
                    ->relationship('levelDefinition', 'level_number')
                    ->required(),
                Forms\Components\TextInput::make('energy_cost')->numeric()->required(),
                Forms\Components\TextInput::make('money_reward_min')->numeric()->required(),
                Forms\Components\TextInput::make('money_reward_max')->numeric()->required(),
                Forms\Components\TextInput::make('experience_reward')->numeric()->required(),
                Forms\Components\TextInput::make('cooldown_seconds')->numeric()->required(),
                Forms\Components\Select::make('primary_attribute')
                    ->options(CharacterAttributeEnum::class)
                    ->required(),
                Forms\Components\TextInput::make('base_success_chance')
                    ->numeric()->required()->minValue(0)->maxValue(100)
                    ->label('Chance de Sucesso Base (%)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('levelDefinition.level_number')->label('Nível Req.')->sortable(),
                Tables\Columns\TextColumn::make('primary_attribute')->badge(),
                Tables\Columns\TextColumn::make('base_success_chance')->suffix('%')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCrimes::route('/'),
            'create' => Pages\CreateCrime::route('/create'),
            'edit' => Pages\EditCrime::route('/{record}/edit'),
        ];
    }
}
