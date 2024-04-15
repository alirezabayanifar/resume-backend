<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Posts';
    protected static ?string $modelLabel = 'Blog Post';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->minLength(3)
                            ->maxLength(250),
                        RichEditor::make('content')
                            ->required()
                            ->minLength(3)
                            ->maxLength(9999999)
                            ->columnSpanFull(),
                    ])->columnSpan(3),
                Forms\Components\Section::make()
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->directory('thumbnails')
                            ->image()
                            ->required()
                            ->maxSize('1024')
                            ->imageEditor()
                            ->openable(),
                        Select::make('categories')
                            ->relationship('categories', 'name')
                            ->label('category')
                            ->searchable()
                            ->multiple()
                            ->preload()
                            ->live()
                            ->required()
                            ->native(false),
                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->label('tag')
                            ->searchable()
                            ->multiple()
                            ->preload()
                            ->live()
                            ->required()
                            ->native(false)
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->minLength(3)
                                    ->maxLength(250)
                                    ->unique(ignoreRecord: true)
                                    ->required()
                            ])
                            ->createOptionAction(function (Action $action) {
                                $action->mutateFormDataUsing(function (array $data): array {
                                    $data['slug'] = slugify($data['name']);
                                    return $data;
                                });
                            }),
                        DateTimePicker::make('showed_at')
                            ->default(now())
                            ->format('Y-m-d')
                            ->displayFormat('Y-m-d')
                            ->seconds(false)
                            ->timezone('America/New_York')
                            ->native(false)
                            ->required(),

                    ])->columnSpan(1)
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->words(10)
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->words(10)
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('categories')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label('tags')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('showed_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('View in site')
                        ->color('info')
                        ->icon('heroicon-m-computer-desktop')
                        ->openUrlInNewTab(),
                ])
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
