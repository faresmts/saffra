<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use App\Models\Supply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $modelLabel = 'Venda';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Financeiro';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supply_id')
                    ->label('Insumo')
                    ->relationship('supply', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->formattedFullName)
                    ->reactive()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(fn (Forms\Get $get) => Supply::query()->find($get('supply_id'))?->stock)
                    ->reactive()
                    ->afterStateUpdated(function (?string $state, Forms\Set $set, Forms\Get $get) {
                        if ($state === null) {
                            return;
                        }

                        $value = Supply::query()->find($get('supply_id'))?->price * $state;
                        $value = $value ? number_format($value, 2, ',', '.') : '0,00';
                        $set('total', "R$ $value");
                    })
                    ->required(),

                Forms\Components\TextInput::make('payer')
                    ->label('Pagante')
                    ->required(),

                Forms\Components\DatePicker::make('sold_at')
                    ->label('Data')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->required(),

                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->readOnly()
                    ->reactive()
                    ->formatStateUsing(fn (?string $state) => 'R$ '. number_format($state, 2, ',', '.'))
                    ->default(0),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supply.name')
                    ->label('Insumo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('payer')
                    ->label('Pagante')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sold_at')
                    ->label('Data')
                    ->date('d/m/Y H:i:s')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSales::route('/'),
        ];
    }
}
