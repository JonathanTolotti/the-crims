<?php
namespace App\Filament\Resources;

use App\Enums\EquipmentSlotEnum;
use App\Enums\ItemTypeEnum;
use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Configurações do Jogo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->columnSpanFull(),
                        Forms\Components\Textarea::make('description')->columnSpanFull(),
                        Forms\Components\FileUpload::make('image_path')->image()->directory('items'),
                        Forms\Components\Toggle::make('stackable')->required(),
                        Forms\Components\Select::make('item_type')
                            ->options(ItemTypeEnum::class)
                            ->required()
                            ->reactive(), // Torna o campo reativo
                    ])->columns(2),

                Forms\Components\Section::make('Equipamento')
                    // Mostra esta seção apenas se o tipo de item for 'equipment'
                    ->visible(fn (callable $get) => $get('item_type') === 'equipment')
                    ->schema([
                        Forms\Components\Select::make('equipment_slot')->options(EquipmentSlotEnum::class),
                        Forms\Components\Select::make('required_level_id')->relationship('requiredLevel', 'level_number'),
                        Forms\Components\TextInput::make('strength_bonus')->numeric()->default(0),
                        Forms\Components\TextInput::make('dexterity_bonus')->numeric()->default(0),
                        Forms\Components\TextInput::make('intelligence_bonus')->numeric()->default(0),
                    ])->columns(2),

                Forms\Components\Section::make('Consumível')
                    // Mostra esta seção apenas se o tipo de item for 'consumable'
                    ->visible(fn (callable $get) => $get('item_type') === 'consumable')
                    ->schema([
                        Forms\Components\TextInput::make('effect_type')->helperText("Ex: RESTORE_ENERGY, ADD_XP"),
                        Forms\Components\TextInput::make('effect_amount')->numeric(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('')->square(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('item_type')->badge()->searchable(),
                Tables\Columns\TextColumn::make('equipment_slot')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('item_type')
                    ->label('Tipo de Item')
                    ->options(ItemTypeEnum::class)
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

}
