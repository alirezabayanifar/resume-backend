<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkResource\Pages;
use App\Filament\Resources\WorkResource\RelationManagers;
use App\Models\Work;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkResource extends Resource
{
    protected static ?string $model = Work::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->minLength(3)
                            ->maxLength(250),
                        Forms\Components\RichEditor::make('summary')
                            ->required()
                            ->minLength(3)
                            ->maxLength(9999999)
                            ->columnSpanFull(),
                    ])->columnSpan(7),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('owner')
                            ->required()
                            ->minLength(3)
                            ->maxLength(250),
                        Forms\Components\TextInput::make('role')
                            ->required()
                            ->minLength(3)
                            ->maxLength(250),
                        Forms\Components\TextInput::make('link')
                            ->required()
                            ->minLength(3)
                            ->maxLength(250),
                        Forms\Components\DateTimePicker::make('date')
                            ->default(now())
                            ->format('Y-m-d')
                            ->displayFormat('Y-m-d')
                            ->seconds(false)
                            ->timezone('America/New_York')
                            ->native(false)
                            ->required(),
                    ])->columnSpan(5),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->directory('thumbnails')
                            ->image()
                            ->required()
                            ->maxSize('1024')
                            ->imageEditor()
                            ->openable(),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('subtitle')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(250),
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
                            ])->columnSpan(1),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->minLength(3)
                            ->maxLength(9999999)
                            ->columnSpan(1),
                    ])->columns(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                            ->label('attachments')
                            ->collection('images')
                            ->acceptedFileTypes(config('filesystems.mimes'))
                            ->multiple()
                            ->openable()
                    ])
            ])->columns(12);
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
                Tables\Columns\TextColumn::make('subtitle')
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListWorks::route('/'),
            'create' => Pages\CreateWork::route('/create'),
            'edit' => Pages\EditWork::route('/{record}/edit'),
        ];
    }
}
