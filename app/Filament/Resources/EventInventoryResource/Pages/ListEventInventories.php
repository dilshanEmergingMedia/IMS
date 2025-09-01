<?php

namespace App\Filament\Resources\EventInventoryResource\Pages;

use App\Filament\Resources\EventInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventInventories extends ListRecords
{
    protected static string $resource = EventInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
