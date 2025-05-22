<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdResource\Pages;
use App\Filament\Resources\AdResource\RelationManagers;
use App\Models\Ad;
use App\Models\Position;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Anúncio';
    protected static ?string $pluralLabel     = 'Anúncios';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'image'  => 'Imagem',
                        'script' => 'Script/HTML',
                    ])
                    ->reactive()
                    ->required(),

                FileUpload::make('image')
                    ->label('Imagem')
                    ->image()
                    ->disk('public')
                    ->directory('ads')
                    ->visibility('public')
                    ->visible(fn (callable $get) => $get('type') === 'image'),


                TextInput::make('link')
                    ->label('Link (URL)')
                    ->url()
                    ->visible(fn (callable $get) => $get('type') === 'image'),

                Textarea::make('html_code')
                ->label('Código HTML/Script')
                ->rows(4)
                ->visible(fn (callable $get) => $get('type') === 'script'),

                Select::make('positions')
                    ->label('Posições de Exibição')
                    ->multiple()
                    ->options(fn () => Position::all()
                        ->pluck('label_with_dimensions', 'id')
                        ->toArray()
                    )
                    ->required(),

                 Select::make('post_id')
                    ->label('Vincular a Post (opcional)')
                    ->relationship('post', 'title')
                    ->searchable()
                    ->nullable(),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true),

                DateTimePicker::make('start_at')
                    ->label('Início de Exibição'),

                DateTimePicker::make('end_at')
                    ->label('Fim de Exibição'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'image'  => 'Imagem',
                        'script' => 'Script',
                        default  => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'image'  => 'primary',
                        'script' => 'secondary',
                    }),

                TextColumn::make('positions.label')
                    ->label('Posições')
                    ->separator(', '),

                TextColumn::make('post.title')
                    ->label('Post Vinculado')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Ativo'),

                TextColumn::make('start_at')
                    ->dateTime()
                    ->label('Início'),

                TextColumn::make('end_at')
                    ->dateTime()
                    ->label('Fim'),
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


    public static function canViewNavigation(): bool
    {
        $user = Auth::user();

        return $user !== null && in_array(needle: $user->role, haystack: ['admin', 'editor']);
    }
}
