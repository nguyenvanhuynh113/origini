<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingResource\Pages;
use App\Filament\Resources\ShippingResource\RelationManagers;
use App\Models\Shipping;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;
    protected static ?string $navigationGroup = 'Admin Panel';
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->required()->columnSpan(3),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('fee')->numeric()
                            ->reactive()
                            ->minValue(1)->maxValue(100),
                        Forms\Components\TextInput::make('estimated_date')->numeric()
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('fee')->sortable(),
                Tables\Columns\TextColumn::make('estimated_date')->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d'),
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
                    ->before(function (Tables\Actions\DeleteAction $action, Shipping $record) {
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
            'index' => Pages\ListShippings::route('/'),
            'create' => Pages\CreateShipping::route('/create'),
            'edit' => Pages\EditShipping::route('/{record}/edit'),
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
