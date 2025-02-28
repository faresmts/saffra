<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MachineryResource\Pages;
use App\Filament\Resources\MachineryResource\Widgets\MachineryMaintenanceAmount;
use App\Models\Machinery;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MachineryResource extends Resource
{
    protected static ?string $model = Machinery::class;
    protected static ?string $modelLabel = 'Maquinário';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Patrimônios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Informações da Máquina')
                ->columns(3)
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->required(),

                    TextInput::make('manufacturer')
                        ->label('Fabricante')
                        ->required(),

                    DatePicker::make('fabricated_at')
                        ->label('Fabricado em')
                        ->displayFormat('d/m/y')
                        ->native(false)
                        ->required(),

                    DatePicker::make('purchased_at')
                        ->label('Comprado em')
                        ->displayFormat('d/m/y')
                        ->native(false)
                        ->required(),

                    TextInput::make('cost')
                        ->label('Valor')
                        ->numeric()
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ]),

                Section::make('Manutenções')
                    ->columns(1)
                    ->schema([
                        Repeater::make('maintenance')
                            ->relationship('maintenance')
                            ->label('Manutenções')
                            ->columns(2)
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Data')
                                    ->displayFormat('d/m/y')
                                    ->native(false)
                                    ->required(),

                                TextInput::make('cost')
                                    ->label('Valor')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required(),
                            ]),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('manufacturer')
                    ->label('Fabricante')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fabricated_at')
                    ->label('Fabricado em')
                    ->formatStateUsing(fn (string $state) => date('d/m/Y', strtotime($state)))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('purchased_at')
                    ->label('Comprado em')
                    ->formatStateUsing(fn (string $state) => date('d/m/Y', strtotime($state)))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cost')
                    ->label('Valor')
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => 'R$ ' . number_format($state, 2, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('maintenanceAmount')
                    ->label('Manutenções')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('maintenanceCost')
                    ->label('Total em Manutenções')
                    ->searchable()
                    ->sortable(),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMachineries::route('/'),
            'create' => Pages\CreateMachinery::route('/create'),
            'edit' => Pages\EditMachinery::route('/{record}/edit'),
        ];
    }
}
