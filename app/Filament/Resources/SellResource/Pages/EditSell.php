<?php

namespace App\Filament\Resources\SellResource\Pages;

use App\Filament\Resources\SellResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSell extends EditRecord
{
    protected static string $resource = SellResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
