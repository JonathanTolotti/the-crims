<?php


namespace App\Filament\Resources;

use App\Enums\StoreProductTypeEnum;
use App\Filament\Resources\StoreProductResource\Pages;
use App\Models\StoreProduct;
use App\Models\VipTier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StoreProductResource extends Resource
{
    protected static ?string $model = StoreProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Loja';
    protected static ?string $modelLabel = 'Produto da Loja';
    protected static ?string $pluralModelLabel = 'Produtos da Loja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->columnSpanFull(),
                        Forms\Components\Textarea::make('description')->columnSpanFull(),
                        Forms\Components\FileUpload::make('image_path')->image()->directory('products'), // Salva em storage/app/public/products
                        Forms\Components\Toggle::make('is_active')->label('Ativo na loja?')->default(true),
                        Forms\Components\TextInput::make('price_in_cents')->required()->numeric()->label('Preço (em centavos)'),
                        Forms\Components\Select::make('product_type')
                            ->options(StoreProductTypeEnum::class)
                            ->required()
                            ->reactive(), // Torna o campo reativo para os campos condicionais abaixo
                    ])->columns(2),

                Forms\Components\Section::make('Metadados do Pacote de Cash')
                    // Mostra esta seção apenas se o tipo de produto for 'cash_package'
                    ->visible(fn(callable $get) => $get('product_type') === 'cash_package')
                    ->schema([
                        Forms\Components\TextInput::make('metadata.cash_amount')
                            ->label('Quantidade de CrimsCoin')
                            ->numeric()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Metadados da Assinatura VIP')
                    // Mostra esta seção apenas se o tipo de produto for 'vip_subscription'
                    ->visible(fn(callable $get) => $get('product_type') === 'vip_subscription')
                    ->schema([
                        Forms\Components\Select::make('metadata.vip_tier_id')
                            ->label('Nível de VIP Associado')
                            ->options(VipTier::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('metadata.duration_in_days')
                            ->label('Duração da Assinatura (dias)')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('')->square(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('product_type')->badge()->searchable(),
                Tables\Columns\TextColumn::make('price_in_cents')->money('BRL', divideBy: 100)->sortable()->label('Preço'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Ativo'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('item_type')->options(StoreProductTypeEnum::class),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoreProducts::route('/'),
            'create' => Pages\CreateStoreProduct::route('/create'),
            'edit' => Pages\EditStoreProduct::route('/{record}/edit'),
        ];
    }
}
