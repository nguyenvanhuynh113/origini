<?php

namespace App\Filament\Widgets;

use App\Models\Oder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Oder::query()->latest()->limit(10);
    }


    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('number')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('user.name'),
            Tables\Columns\TextColumn::make('payment')->searchable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'danger' => 'cancelled',
                    'warning' => 'processing',
                    'success' => fn($state) => in_array($state, ['delivered', 'shipped']),
                ]),
            Tables\Columns\TextColumn::make('items_count')
                ->label('Items')
                ->counts('items'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Order Date')
                ->date(),
        ];
    }

    protected function getActions(): array
    {
        return [
            Tables\Actions\EditAction::make()
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
        ];
    }
}
