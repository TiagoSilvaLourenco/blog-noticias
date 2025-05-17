<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === 'admin' ? true : false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->visible(fn ($livewire): bool =>
                        Auth::user()->role === 'admin'
                        || ($livewire instanceof EditRecord
                            && Auth::user()->role === 'editor'
                            && $livewire->getRecord()->id === Auth::user()->id)
                    ),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->visible(fn ($livewire): bool =>
                        Auth::user()->role === 'admin'
                    ),

                Select::make('role')
                    ->options([
                        'admin'  => 'Admin',
                        'editor' => 'Editor',
                        'user'   => 'User',
                    ])
                    ->required()
                    ->visible(fn (): bool =>
                        Auth::user()->role === 'admin'
                    ),

                Toggle::make('status')
                    ->label('Ativo')
                    ->visible(fn (): bool =>
                        Auth::user()->role === 'admin'
                    ),

                TextInput::make('password')
                    ->password()
                    ->visible(fn ($livewire): bool =>
                        ( $livewire instanceof CreateRecord
                            && Auth::user()->role === 'admin')
                        || ($livewire instanceof EditRecord
                            && (
                                Auth::user()->role === 'admin'
                                || (Auth::user()->role === 'editor'
                                    && $livewire->getRecord()->id === Auth::user()->id
                                )
                            ))
                    )
                    ->required(fn ($livewire) => $livewire instanceof CreateRecord)
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state)),

                TextInput::make('password_confirmation')
                    ->password()
                    ->label('Confirme a senha')
                    ->visible(fn ($livewire): bool =>
                        ( $livewire instanceof CreateRecord
                            && Auth::user()->role === 'admin')
                        || ($livewire instanceof EditRecord
                            && Auth::user()->role === 'admin')
                        || ($livewire instanceof EditRecord
                            && Auth::user()->role === 'editor'
                            && $livewire->getRecord()->id === Auth::user()->id
                        )
                    )
                    ->required(fn ($livewire) => $livewire instanceof CreateRecord),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role')
                ->badge()                           // transforma em badge
                ->color(fn (string $state): string => match ($state) {
                    'admin'  => 'primary',
                    'editor' => 'warning',
                    'user'   => 'secondary',
                }),
                IconColumn::make('status')->boolean(),

            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user !== null && in_array($user->role, ['admin', 'editor']);
    }
}
