<?php

namespace App\Filament\Resources\OderResource\Pages;

use App\Filament\Resources\OderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOder extends EditRecord
{
    protected static string $resource = OderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
