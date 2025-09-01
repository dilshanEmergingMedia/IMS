<?php

namespace App\Filament\Resources\EventInventoryResource\Pages;

use App\Filament\Resources\EventInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventInventory extends EditRecord
{
    protected static string $resource = EventInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
