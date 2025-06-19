<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Jogadores';

    protected static ?string $modelLabel = 'Jogador';
    protected static ?string $pluralModelLabel = 'Jogadores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Principais')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('email')->email()->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Status e Permissões')
                    ->schema([
                        Forms\Components\Toggle::make('is_admin')->label('É Administrador?'),
                        Forms\Components\Toggle::make('is_vip')->label('É VIP?')->reactive(), // 'reactive' faz a UI se atualizar
                        Forms\Components\DateTimePicker::make('vip_expires_at')
                            ->label('VIP Expira em')
                            // Este campo só aparece se o Toggle 'is_vip' estiver ativado
                            ->visible(fn (callable $get) => $get('is_vip')),
                    ])->columns(3),

                Forms\Components\Section::make('Atributos e Stats do Jogo')
                    ->schema([
                        Forms\Components\Select::make('character_class_id')->relationship('characterClass', 'name'),
                        Forms\Components\Select::make('current_level_id')->relationship('levelDefinition', 'level_number'),
                        Forms\Components\TextInput::make('experience_points')->numeric(),
                        Forms\Components\TextInput::make('money')->numeric(),
                        Forms\Components\TextInput::make('crims_coin')->numeric(),
                        Forms\Components\TextInput::make('energy_points')->numeric(),
                        Forms\Components\TextInput::make('max_energy_points')->numeric(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('characterClass.name')->label('Classe')->sortable(),
                Tables\Columns\TextColumn::make('levelDefinition.level_number')->label('Nível')->sortable(),
                Tables\Columns\IconColumn::make('is_vip')->boolean()->label('VIP'),
                Tables\Columns\IconColumn::make('is_admin')->boolean()->label('Admin'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                // Filtros podem ser adicionados aqui
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(), // Adiciona um botão para visualizar (read-only)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\InventoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
