<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdResource\Pages;
use App\Filament\Resources\AdResource\RelationManagers;
use App\Models\Ad;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image')->label('Banner')->image(),
                Textarea::make('html_code'),
                Select::make('type')
                    ->label('Tipo')
                    ->options(['image'=>'Imagem','script'=>'Script']),
                TextInput::make('link')->url(),
                TextInput::make('position')->required(),
                Select::make('post_id')
                    ->label('Vincular a Post')
                    ->options(Post::all()->pluck('title','id')),
                Toggle::make('is_active'),
                DateTimePicker::make('start_at'),
                DateTimePicker::make('end_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('post.title')
                    ->label('Post')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_active')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListAds::route('/'),
            'create' => Pages\CreateAd::route('/create'),
            'edit' => Pages\EditAd::route('/{record}/edit'),
        ];
    }
}
