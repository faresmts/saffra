<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $modelLabel = 'Propriedade';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Patrimônios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nome')
                        ->columnSpan(3),
                    Forms\Components\TextInput::make('size')
                        ->required()
                        ->numeric()
                        ->inputMode('decimal')
                        ->label('Tamanho (em hectáres)')
                        ->minValue(1),
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->maxLength(255)
                        ->label('Endereço')
                        ->columnSpan(3),
                    Forms\Components\Toggle::make('is_active')
                        ->required()
                        ->default(false)
                        ->label('está ativa?')
                        ->inline(false),
                ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')->label('Endereço'),
                Tables\Columns\TextColumn::make('size')->label('Tamanho (em hectáres)'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativa?')
                    ->boolean(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
