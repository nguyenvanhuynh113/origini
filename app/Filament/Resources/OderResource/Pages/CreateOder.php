<?php

namespace App\Filament\Resources\OderResource\Pages;

use App\Filament\Resources\OderResource;
use App\Filament\Resources\Shop\OrderResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOder extends CreateRecord
{
    protected static string $resource = OderResource::class;
    protected function afterCreate(): void
    {
        $order = $this->record;
        $name=auth()->user()->name;
        Notification::make()
            ->title('New order')
            ->icon('heroicon-o-shopping-bag')
            ->body("**{$name} ordered {$order->items->count()} products.**")
            ->actions([
                Action::make('View')
                    ->url(OderResource::getUrl('edit', ['record' => $order])),
            ])
            ->sendToDatabase(auth()->user());
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Order Details')
                ->schema([
                    Section::make()->schema(OderResource::getFormSchema())->columns(),
                ]),

            Step::make('Order Items')
                ->schema([
                    Section::make()->schema(OderResource::getFormSchema('items')),
                ]),
        ];
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_user'] = auth()->id();

        return $data;
    }
}
