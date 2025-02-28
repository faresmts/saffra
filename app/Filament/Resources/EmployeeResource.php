<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $modelLabel = 'Colaborador';
    protected static ?string $pluralModelLabel = 'Colaboradores';

    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required(),

                Forms\Components\TextInput::make('raw_salary')
                    ->label('Salário Bruto')
                    ->numeric()
                    ->minValue(0.1)
                    ->required(),

                Forms\Components\TextInput::make('salary')
                    ->label('Salário Líquido')
                    ->numeric()
                    ->minValue(0.1)
                    ->required(),

                Forms\Components\TextInput::make('function')
                    ->label('Função')
                    ->required(),
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

                Tables\Columns\TextColumn::make('raw_salary')
                    ->label('Salário Bruto')
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => 'R$ '. number_format($state, 2, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('salary')
                    ->label('Salário Líquido')
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => 'R$ '. number_format($state, 2, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('function')
                    ->label('Função')
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
            'index' => Pages\ManageEmployees::route('/'),
        ];
    }
}
