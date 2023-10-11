<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\GlobalSearch\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Admin Panel';
    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function (Closure $get, Closure $set, ?string $state) {
                                if (!$get('is_slug_changed_manually') && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->reactive(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('quantity')
                            ->default(0)
                            ->required()->numeric()
                            ->minValue(0)->maxValue(100),
                        Forms\Components\TextInput::make('unit_prices')
                            ->default(1)
                            ->required()->numeric()
                            ->minValue(1),
                        Select::make('categories')
                            ->multiple()->label('Category')
                            ->relationship('categories', 'name')
                            ->searchable()->preload()
                            ->createOptionForm([
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
                                    ->modalHeading('Create category')
                                    ->modalButton('Create category')
                                    ->modalWidth('lg');
                            }),
                        Select::make('tags')->label('Tag')
                            ->multiple()
                            ->relationship('tags', 'name')
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
                                    ->modalHeading('Create tag')
                                    ->modalButton('Create tag')
                                    ->modalWidth('lg');
                            }),
                        Select::make('shipings')->label('Shipping')
                            ->multiple()
                            ->relationship('shipings', 'name')
                            ->searchable()->preload()
                            ->createOptionForm([
                                Forms\Components\Toggle::make('active')
                                    ->required()->default(1),
                                Forms\Components\TextInput::make('name')
                                    ->required()->unique()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('fee')->numeric()
                                    ->reactive()
                                    ->minValue(1)->maxValue(100),
                                Forms\Components\TextInput::make('estimated_date')->numeric()
                            ])
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Create shipping')
                                    ->modalButton('Create shipping')
                                    ->modalWidth('lg');
                            }),
                        Select::make('coupons')->label('Coupon')
                            ->multiple()
                            ->relationship('coupons', 'code')
                            ->searchable()->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('code')
                                    ->default('GIAMGIA-' . random_int(9999, 100000))
                                    ->required()
                                    ->maxLength(255)->columnSpan(2),
                                Forms\Components\DatePicker::make('coupon_star_date')
                                    ->required(),
                                Forms\Components\DatePicker::make('coupon_end_date')
                                    ->required(),
                                Forms\Components\TextInput::make('discount_value')->numeric()
                                    ->required()->minValue(0.000001)->default(0.1),
                                Forms\Components\TextInput::make('quantity')->numeric()
                                    ->required()->numeric()->minValue(1)->default(1),
                            ])
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Create coupon')
                                    ->modalButton('Create coupon')
                                    ->modalWidth('lg');
                            }),
                        Forms\Components\FileUpload::make('image')->columnSpan(2),
                        Forms\Components\RichEditor::make('description')
                            ->maxLength(65535)->columnSpan(2),
                        Forms\Components\RichEditor::make('information')
                            ->maxLength(65535)->columnSpan(2),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('unit_prices'),
                Tables\Columns\ImageColumn::make('image')->circular(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\CategoriesRelationManager::class,
            RelationManagers\TagsRelationManager::class,
            RelationManagers\CouponsRelationManager::class,
            RelationManagers\ShipingsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->name,
            'unit_prices' => $record->unit_prices,
            'quantity' => $record->quantity

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
