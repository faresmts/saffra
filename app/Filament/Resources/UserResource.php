<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Support\Enums\ValidationMessagesEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->validationMessages([
                            'required' => ValidationMessagesEnum::REQUIRED,
                            'maxLength' => ValidationMessagesEnum::MAX_LENGTH,
                        ])
                        ->label('Nome')
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255)
                        ->validationMessages([
                            'unique' => ValidationMessagesEnum::UNIQUE->value,
                            'email' => ValidationMessagesEnum::EMAIL->value,
                            'required' => ValidationMessagesEnum::REQUIRED->value,
                            'maxLength' => ValidationMessagesEnum::MAX_LENGTH->value,
                        ])
                        ->label('E-mail')
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->required(fn (string $context): bool => $context === 'create')
                        ->maxLength(255)
                        ->validationMessages([
                            'required' => ValidationMessagesEnum::REQUIRED->value,
                            'maxLength' => ValidationMessagesEnum::MAX_LENGTH->value,
                            'same' => ValidationMessagesEnum::SAME->value,
                        ])
                        ->dehydrated(fn ($state): bool => filled($state))
                        ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                        ->live(debounce: 500)
                        ->label('Senha')
                        ->same('password_confirmation')
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->password()
                        ->revealable()
                        ->required(fn (string $context): bool => $context === 'create')
                        ->maxLength(255)
                        ->validationMessages([
                            'required' => ValidationMessagesEnum::REQUIRED->value,
                            'maxLength' => ValidationMessagesEnum::MAX_LENGTH->value,
                        ])
                        ->label('Confirme a senha')
                        ->dehydrated(false)
                        ->columnSpan(3),
                ])->columns(6)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
