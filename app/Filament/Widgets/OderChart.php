<?php

namespace App\Filament\Widgets;

use App\Models\Oder;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OderChart extends LineChartWidget
{
    protected static ?string $heading = 'Oder Chart';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Trend::model(Oder::class)
            ->between(
                start: now()->startOfDay(),
                end: now()->endOfDay(),
            )
            ->perHour()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#98FB98',
                    'borderColor' => '#98FB98',
                    'fill' => false
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }
}
