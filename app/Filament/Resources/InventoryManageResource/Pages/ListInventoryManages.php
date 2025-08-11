<?php

namespace App\Filament\Resources\InventoryManageResource\Pages;

use App\Filament\Resources\InventoryManageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryManages extends ListRecords
{
    protected static string $resource = InventoryManageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
