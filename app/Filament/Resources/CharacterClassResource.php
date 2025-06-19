<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharacterClassResource\Pages;
use App\Models\CharacterClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CharacterClassResource extends Resource
{
    protected static ?string $model = CharacterClass::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Configurações do Jogo';

    protected static ?string $modelLabel = 'Classe de Personagem';
    protected static ?string $pluralModelLabel = 'Classes de Personagem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Fieldset::make('Modificadores de Atributos (em porcentagem, ex: 0.10 para 10%)')
                    ->schema([
                        Forms\Components\TextInput::make('strength_modifier')
                            ->required()
                            ->numeric()
                            ->default(0.000),
                        Forms\Components\TextInput::make('dexterity_modifier')
                            ->required()
                            ->numeric()
                            ->default(0.000),
                        Forms\Components\TextInput::make('intelligence_modifier')
                            ->required()
                            ->numeric()
                            ->default(0.000),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('strength_modifier')->numeric(3)->sortable(),
                Tables\Columns\TextColumn::make('dexterity_modifier')->numeric(3)->sortable(),
                Tables\Columns\TextColumn::make('intelligence_modifier')->numeric(3)->sortable(),
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
            'index' => Pages\ListCharacterClasses::route('/'),
            'create' => Pages\CreateCharacterClass::route('/create'),
            'edit' => Pages\EditCharacterClass::route('/{record}/edit'),
        ];
    }
}
