<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OderResource\Pages;
use App\Filament\Resources\OderResource\RelationManagers;
use App\Models\Oder;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


class OderResource extends Resource
{
    protected static ?string $model = Oder::class;
    protected static ?string $navigationGroup = 'Admin Panel';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Lasted Oder')
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn(Oder $record): ?string => $record->created_at?->diffForHumans()),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn(Oder $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 2])
                    ->hidden(fn(?Oder $record) => $record === null),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Oder')
                            ->schema(static::getFormSchema())
                            ->columns(2),

                        Forms\Components\Section::make('Order items')
                            ->schema(static::getFormSchema('items')),
                    ])
                    ->columnSpan(['lg' => fn(?Oder $record) => $record === null ? 3 : 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'processing',
                        'success' => fn($state) => in_array($state, ['delivered', 'shipped']),
                    ]),
                Tables\Columns\TextColumn::make('total_price')->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOders::route('/'),
            'create' => Pages\CreateOder::route('/create'),
            'edit' => Pages\EditOder::route('/{record}/edit'),
        ];
    }

    public static function getFormSchema(string $section = null): array
    {
        if ($section === 'items') {
            return [
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('id_product')
                            ->label('Product')
                            ->options(\App\Models\Product::query()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, \Closure $set) => $set('unit_prices', Product::find($state)?->unit_prices ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ])
                            ->searchable(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()->minValue(1)
                            ->default(1)
                            ->columnSpan([
                                'md' => 2,
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('unit_prices')
                            ->label('Unit Price')
                            ->disabled()
                            ->dehydrated()
                            ->numeric()
                            ->required()
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->orderable()
                    ->defaultItems(1)
                    ->disableLabel()
                    ->columns([
                        'md' => 10,
                    ])
                    ->required(),
            ];
        }

        return [
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('number')
                        ->default('OR-' . random_int(100000, 999999))
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])->default('new')
                        ->required(),
                    Forms\Components\Select::make('payment')
                        ->label('Payment')
                        ->relationship('payment', 'payment_name')
                        ->searchable()->preload(),
                    Forms\Components\Select::make('shipping')
                        ->label('Shipping')
                        ->relationship('shipping', 'name')
                        ->searchable()->preload()
                        ->createOptionForm([
                            Forms\Components\Toggle::make('active')
                                ->required()->default(1),
                            Forms\Components\TextInput::make('name')
                                ->required()->unique()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('fee')->numeric()
                                ->reactive()->default(0.2)
                                ->minValue(0.01)->maxValue(100),
                            Forms\Components\TextInput::make('estimated_date')->numeric()->minValue(1)
                                ->maxValue(10)->default(1)
                        ])
                        ->
                        createOptionAction(function (Forms\Components\Actions\Action $action) {
                            return $action
                                ->modalHeading('Create shipping')
                                ->modalButton('Create shipping')
                                ->modalWidth('lg');
                        }),
                    Forms\Components\Select::make('coupon')
                        ->label('Coupon')
                        ->relationship('coupon', 'code')->searchable()->preload()
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
                    Forms\Components\Placeholder::make('Total')->content(function ($get) {
                        $sum = 0;
                        foreach ($get('items') as $product) {
                            $sum = $sum + ($product['unit_prices'] * $product['quantity']);
                        }
                        return $sum;
                    }),
                    Forms\Components\MarkdownEditor::make('notes')
                        ->columnSpan('full'),
                ])->columns(3)
        ];
    }
}
