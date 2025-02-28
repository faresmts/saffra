<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplyResource\Pages;
use App\Models\Supply;
use App\Support\Enums\UnityEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;
    protected static ?string $modelLabel = 'Insumo';

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Patrimônios';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                TextInput::make('stock')
                    ->numeric()
                    ->label('Estoque Atual')
                    ->required(),

                TextInput::make('price')
                    ->numeric()
                    ->minValue(0.1)
                    ->step(0.01)
                    ->label('Preço')
                    ->required(),

                Select::make('unit')
                    ->label('Unidade')
                    ->options(UnityEnum::options())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Estoque Atual')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('formattedPrice')
                    ->label('Preço')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('formattedUnit')
                    ->label('Unidade')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
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
            'index' => Pages\ManageSupplies::route('/'),
        ];
    }
}
