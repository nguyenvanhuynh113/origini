<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Oder;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UserOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('User register', User::all()->sortByDesc('created_at')->count())
                ->description(User::all()->sortByDesc('created_at')->count() . 'increase')
                ->chart([7, 10, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-s-trending-down')
                ->color('warning'),
            Card::make('Oder', Oder::all()->sortByDesc('created_at')->count())
                ->description(Oder::all()->sortByDesc('created_at')->count() . 'increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 10, 10, 3, 15, 4, 17])
                ->color('danger'),
            Card::make('Blog Post', Blog::all()->sortByDesc('created_at')->count())
                ->description(Blog::all()->sortByDesc('created_at')->count() . 'increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('secondary'),
            Card::make('Product', Product::all()->sortByDesc('created_at')->count())
                ->description(Product::all()->sortByDesc('created_at')->count() . 'increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
