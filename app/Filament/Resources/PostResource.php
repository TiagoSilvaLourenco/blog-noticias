<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateTimeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->reactive()
                    ->debounce('1300ms')
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('excerpt')->rows(3)->label('Resumo'),
                RichEditor::make('content')
                    ->label('Conteúdo')
                    ->required()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('posts')
                    ->fileAttachmentsVisibility('public'),
                FileUpload::make('cover')
                    ->label('Imagem de Capa')
                    ->image()
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    // ->avatar()
                    ->disk('public')
                    ->directory('covers')
                    ->visibility('public')
                    ->imagePreviewHeight('250')
                    ->preserveFilenames()
                    ->deletable(),

                Select::make('category_id')
                    ->label('Categoria')
                    ->options(Category::all()->pluck('name','id'))
                    ->required(),
                Select::make('tags')
                    ->multiple()
                    ->relationship('tags','name'),
                Select::make('user_id')
                    ->label('Autor')
                    ->options(User::all()->pluck('name','id'))
                    ->required(),
                Toggle::make('is_featured')->label('Destaque'),
                // Toggle::make('comments_enabled')->label('Comentários'),
                Select::make('status')
                    ->options([
                        'draft'     => 'Rascunho',
                        'schedule' => 'Agendado',
                        'published' => 'Publicado',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        ($state === 'draft') ? ($set('published_at', null)) :
                        ($state === 'published' ? $set('published_at', now()) : null)
                    )
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Data de Publicação')
                    ->visible(fn (callable $get) => $get('status') === 'schedule')
                    ->required(fn (callable $get) => $get('status') === 'schedule'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('author.name')->label('Autor')->sortable(),
                TextColumn::make('category.name')->label('Categoria'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published'  => 'success',
                        'schedule' => 'warning',
                        'draft'   => 'secondary',
                    }),
                IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'draft' => 'heroicon-o-pencil',
                        'schedule' => 'heroicon-o-clock',
                        'published' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'published'  => 'success',
                        'schedule' => 'warning',
                        'draft'   => 'secondary',
                    }),
                TextColumn::make('published_at')
                    ->label('Publicado em')
                    ->sortable()
                    ->dateTime()
                    ->formatStateUsing(fn ($state, $record): string =>
                        ($record->status !== 'draft' && filled($state))
                            ? date('d/m/Y H:i', strtotime($state))
                            : '—'
                    ),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
