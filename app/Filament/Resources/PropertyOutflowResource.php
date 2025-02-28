<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyOutflowResource\Pages;
use App\Models\PropertyOutflow;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PropertyOutflowResource extends Resource
{
    protected static ?string $model = PropertyOutflow::class;

    protected static ?string $modelLabel = 'Gastos de Propriedades';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Financeiro';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\TextInput::make('description')
                        ->label('Descrição')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(4),

                    Forms\Components\DatePicker::make('date')
                        ->label('Data da saída')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required(),

                    Forms\Components\TextInput::make('value')
                        ->label('Valor')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->minValue(1),

                    Forms\Components\Select::make('property_id')
                        ->label('Propriedade')
                        ->relationship('property', 'name')
                        ->required(),
                ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição'),

                Tables\Columns\TextColumn::make('value')
                    ->label('Valor'),

                Tables\Columns\TextColumn::make('date')
                    ->label('Data da saída')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('property.name')
                    ->label('Propriedade'),

            ])->defaultSort('date', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListPropertyOutflows::route('/'),
            'create' => Pages\CreatePropertyOutflow::route('/create'),
            'edit' => Pages\EditPropertyOutflow::route('/{record}/edit'),
        ];
    }
}
