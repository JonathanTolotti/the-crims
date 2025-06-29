<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VipTierResource\Pages;
use App\Models\VipTier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VipTierResource extends Resource
{
    protected static ?string $model = VipTier::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Regras do Jogo';
    protected static ?string $modelLabel = 'Pacote VIP';
    protected static ?string $pluralModelLabel = 'Pacotes VIP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Pacote')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price_in_cents')
                            ->required()
                            ->numeric()
                            ->label('Preço (em centavos)')
                            ->helperText('Ex: 999 para R$ 9,99'),
                        Forms\Components\TextInput::make('duration_in_days')
                            ->required()
                            ->numeric()
                            ->label('Duração (em dias)')
                            ->default(30),
                    ])->columns(2),

                Forms\Components\Section::make('Benefícios do Pacote')
                    ->schema([
                        Forms\Components\TextInput::make('reward_multiplier')
                            ->required()
                            ->numeric()
                            ->label('Multiplicador de Recompensa')
                            ->helperText('Ex: 1.20 para +20% de XP/Dinheiro'),
                        Forms\Components\TextInput::make('drop_rate_multiplier')
                            ->required()
                            ->numeric()
                            ->label('Multiplicador de Drop'),
                        Forms\Components\TextInput::make('cooldown_reduction_multiplier')
                            ->required()
                            ->numeric()
                            ->label('Multiplicador de Redução de Cooldown')
                            ->helperText('Ex: 0.80 para -20% de tempo'),
                        Forms\Components\TextInput::make('max_energy_bonus')
                            ->required()
                            ->numeric()
                            ->label('Bônus de Energia Máxima'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price_in_cents')
                    ->label('Preço')
                    ->money('BRL', divideBy: 100) // Formata centavos para R$
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_in_days')
                    ->label('Duração')
                    ->suffix(' dias')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVipTiers::route('/'),
            'create' => Pages\CreateVipTier::route('/create'),
            'edit' => Pages\EditVipTier::route('/{record}/edit'),
        ];
    }
}
