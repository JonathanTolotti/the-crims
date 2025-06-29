<?php
namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Loja';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Pedidos';

    public static function canCreate(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detalhes do Pedido')
                    ->schema([
                        Placeholder::make('user_name')
                            ->label('Jogador')
                            ->content(fn (?Order $record): string => $record?->user?->name ?? 'N/A'),
                        Placeholder::make('store_product_name')
                            ->label('Produto Comprado')
                            ->content(fn (?Order $record): string => $record?->storeProduct?->name ?? 'N/A'),
                        Placeholder::make('status')
                            ->label('Status')
                            ->content(fn (?Order $record): string => $record?->status->value ?? 'N/A'),
                        Placeholder::make('total_in_cents')
                            ->label('Valor Pago')
                            ->content(fn (?Order $record): string => 'R$ ' . number_format(($record?->total_in_cents ?? 0) / 100, 2, ',', '.')),
                        Placeholder::make('created_at')
                            ->label('Data do Pedido')
                            ->content(fn (?Order $record): string => $record?->created_at?->format('d/m/Y H:i:s') ?? 'N/A'),
                        Placeholder::make('stripe_session_id')
                            ->label('ID da Sessão Stripe')
                            ->content(fn (?Order $record): string => $record?->stripe_session_id ?? 'N/A'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Jogador')->searchable(),
                Tables\Columns\TextColumn::make('storeProduct.name')->label('Produto'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (OrderStatusEnum $state): string => match ($state) {
                        OrderStatusEnum::PENDING => 'warning',
                        OrderStatusEnum::COMPLETED => 'success',
                        OrderStatusEnum::FAILED => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_in_cents')->money('BRL', divideBy: 100)->label('Valor'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Data')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
