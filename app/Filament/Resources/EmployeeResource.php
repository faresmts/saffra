<?php

namespace App\Filament\Resources;

use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Colaboradores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome_completo')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('salario_bruto')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01),
                    
                Forms\Components\TextInput::make('salario_liquido')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01)
                    ->rules([
                        Rule::when(
                            fn ($get) => $get('salario_bruto') !== null,
                            ['lte:' . $get('salario_bruto')]
                        )
                    ]),
                    
                Forms\Components\TextInput::make('funcao')
                    ->label('Função')
                    ->required()
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('salario_bruto')
                    ->money('BRL'),
                    
                Tables\Columns\TextColumn::make('salario_liquido')
                    ->money('BRL'),
                    
                Tables\Columns\TextColumn::make('funcao')
                    ->label('Função')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            EmployeeResource\Widgets\SalaryStats::class,
        ];
    }
}