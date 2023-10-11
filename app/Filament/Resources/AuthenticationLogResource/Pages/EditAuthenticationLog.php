<?php

namespace App\Filament\Resources\AuthenticationLogResource\Pages;

use App\Filament\Resources\AuthenticationLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuthenticationLog extends EditRecord
{
    protected static string $resource = AuthenticationLogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
