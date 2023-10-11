<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\GlobalSearch\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $navigationGroup = 'Blog';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $recordTitleAttribute = 'title';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()->unique(ignoreRecord: true)
                            ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                                if (!$get('is_slug_changed_manually') && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->reactive()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Select::make('keys')->label('Key')
                            ->multiple()
                            ->relationship('keys', 'name')
                            ->searchable()
                            ->preload()->createOptionForm([
                                Forms\Components\Toggle::make('active')
                                    ->required()->default(1),
                                Forms\Components\TextInput::make('name')
                                    ->required()->unique()
                                    ->maxLength(255)
                                    ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                                        if (!$get('is_slug_changed_manually') && filled($state)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->reactive(),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                            ])
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Create key')
                                    ->modalButton('Create key')
                                    ->modalWidth('lg');
                            }),
                        Select::make('types')->label('Type')
                            ->multiple()
                            ->relationship('types', 'name')
                            ->searchable()
                            ->preload()->createOptionForm([
                                Forms\Components\Toggle::make('active')
                                    ->required()->default(1),
                                Forms\Components\TextInput::make('name')
                                    ->required()->unique()
                                    ->maxLength(255)
                                    ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                                        if (!$get('is_slug_changed_manually') && filled($state)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    })
                                    ->reactive(),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                            ])
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Create key')
                                    ->modalButton('Create key')
                                    ->modalWidth('lg');
                            }),
                        Forms\Components\FileUpload::make('image')->columnSpan(2),
                        Forms\Components\RichEditor::make('content')->columnSpan(2)
                            ->required()
                    ])->columns(2)
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable()->limit(30),
                Tables\Columns\TextColumn::make('slug')->sortable()->limit(30),
                Tables\Columns\ImageColumn::make('image')->circular(),
                Tables\Columns\TextColumn::make('created_at')->sortable()
                    ->dateTime('Y-m-d'),
            ])
            ->filters([
                //
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->
            bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name'=>$record->title,
        ];
    }
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->iconButton()
                ->icon('heroicon-s-pencil')
                ->url(static::getUrl('edit', ['record' => $record]))
        ];
    }
}
