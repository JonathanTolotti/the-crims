<?php
namespace App\Filament\Resources;

use App\Filament\Resources\CrimeLogResource\Pages;
use App\Models\CrimeLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrimeLogResource extends Resource
{
    protected static ?string $model = CrimeLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Relatórios';
    protected static ?string $modelLabel = 'Log de Crime';
    protected static ?string $pluralModelLabel = 'Logs de Crimes';

    public static function form(Form $form): Form
    {
        // O formulário é usado na página de visualização.
        // Usamos ->disabled() para tornar todos os campos somente leitura.
        return $form
            ->schema([
                Forms\Components\Section::make('Detalhes do Log')
                    ->schema([
                        Forms\Components\Placeholder::make('user_name')
                            ->label('Jogador')
                            ->content(fn (?CrimeLog $record): string => $record?->user?->name ?? 'N/A'),

                        Forms\Components\Placeholder::make('crime_name')
                            ->label('Crime')
                            ->content(fn (?CrimeLog $record): string => $record?->crime?->name ?? 'N/A'),

                        Forms\Components\Placeholder::make('was_successful')
                            ->label('Teve Sucesso?')
                            ->content(fn (?CrimeLog $record): string => $record?->was_successful ? 'Sim' : 'Não'),

                        Forms\Components\Placeholder::make('money_gained')
                            ->label('Dinheiro Ganho')
                            ->content(fn (?CrimeLog $record): string => 'R$ ' . number_format($record?->money_gained ?? 0, 0, ',', '.')),

                        Forms\Components\Placeholder::make('experience_gained')
                            ->label('XP Ganha')
                            ->content(fn (?CrimeLog $record): int => $record?->experience_gained ?? 0),

                        Forms\Components\Placeholder::make('attempted_at')
                            ->label('Data da Tentativa')
                            ->content(fn (?CrimeLog $record): string => $record?->attempted_at?->format('d/m/Y H:i:s') ?? 'N/A'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Jogador')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('crime.name')->label('Crime')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('was_successful')->boolean()->label('Sucesso'),
                Tables\Columns\TextColumn::make('money_gained')->money('BRL'),
                Tables\Columns\TextColumn::make('attempted_at')->label('Data')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('crime')->relationship('crime', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('attempted_at', 'desc');
    }

    // Remove as rotas de criação e edição
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCrimeLogs::route('/'),
            'view' => Pages\ViewCrimeLog::route('/{record}'), // Adiciona a página de visualização
        ];
    }

    // Impede a criação de novos logs pelo painel
    public static function canCreate(): bool
    {
        return false;
    }
}
