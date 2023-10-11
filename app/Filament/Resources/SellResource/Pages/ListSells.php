<?php

namespace App\Filament\Resources\SellResource\Pages;

use App\Filament\Resources\SellResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSells extends ListRecords
{
    protected static string $resource = SellResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
