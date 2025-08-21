<?php

namespace App\Filament\Resources\SpareModelResource\Pages;

use App\Filament\Resources\SpareModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpareModels extends ListRecords
{
    protected static string $resource = SpareModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
