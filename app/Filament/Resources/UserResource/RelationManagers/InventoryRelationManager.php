<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryRelationManager extends RelationManager
{
    protected static string $relationship = 'inventory';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Dropdown para selecionar o item
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name') // Busca itens pela relação 'item' e mostra o 'name'
                    ->searchable() // Permite pesquisar itens
                    ->preload() // Pré-carrega as opções para agilidade
                    ->required()
                    ->label('Item'),

                // Campos para os detalhes da instância do item
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->label('Quantidade'),

                Forms\Components\TextInput::make('tier')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->label('Tier'),

                Forms\Components\Toggle::make('is_equipped')
                    ->label('Equipado?'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_id')
            ->columns([
                Tables\Columns\ImageColumn::make('item.image_path')->label('')->square(),
                Tables\Columns\TextColumn::make('item.name')->label('Item'),
                Tables\Columns\TextColumn::make('quantity')->label('Qtd.'),
                Tables\Columns\TextColumn::make('tier')->label('Tier'),
                Tables\Columns\IconColumn::make('is_equipped')->boolean()->label('Equipado'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
