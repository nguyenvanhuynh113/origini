<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static ?string $navigationGroup = 'Admin Panel';
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
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
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('discount_value'),
                Tables\Columns\TextColumn::make('coupon_star_date')
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('coupon_end_date')
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('quantity'),
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
                Tables\Actions\ForceDeleteBulkAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Coupon $record) {
                        if ($record->products()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Failed to delete!')
                                ->body('Category has products.')
                                ->persistent()
                                ->send();

                            // This will halt and cancel the delete action modal.
                            $action->cancel();
                        }
                    }),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
