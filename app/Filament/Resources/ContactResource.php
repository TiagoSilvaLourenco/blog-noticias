<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Mensagens';
    protected static ?string $pluralLabel     = 'Mensagens Recebidas';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Section::make('Detalhes do Contato')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->disabled(),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->disabled(),
                Forms\Components\TextInput::make('company')
                    ->label('Empresa')
                    ->disabled(),
                Forms\Components\TextInput::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn($state) => match (mb_strtolower($state)) {
                        'advertise', 'anunciar' => 'Anunciar',
                        'contact', 'contato'   => 'Contato',
                        default                 => ucfirst($state),
                    })
                    ->disabled(),
                Forms\Components\Textarea::make('message')
                    ->label('Mensagem')
                    ->rows(6)
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'new'      => 'Novo',
                        'read'     => 'Lido',
                        'replied'  => 'Respondido',
                    ])
                    ->disabled(),
            ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('company')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn($state) => match (mb_strtolower($state)) {
                        'advertise', 'anunciar' => 'Anunciar',
                        'contact', 'contato'   => 'Contato',
                        default                 => ucfirst($state),
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Mensagem')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn ($state) => match ($state) {
                    'read' => 'Lido',
                    'replied' => 'Respondido',
                    default => 'Novo',
                })
                ->colors([
                    'primary' => 'new',    // novo = primary
                    'success' => 'read',   // lido = success
                    'warning' => 'replied',// respondido = warning
                ]),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Recebido em')
                ->dateTime('d/m/Y H:i'),
            ])->filters([
                //
            ])->headerActions([

            ])->actions([
                //
            ])->bulkActions([])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListContacts::route('/'),
            'view'  => Pages\ViewContact::route('/{record}'),
        ];
    }
}
