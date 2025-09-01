<?php

namespace App\Filament\Resources\EventInventoryResource\Pages;

use App\Filament\Resources\EventInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventInventory extends CreateRecord
{
    protected static string $resource = EventInventoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
