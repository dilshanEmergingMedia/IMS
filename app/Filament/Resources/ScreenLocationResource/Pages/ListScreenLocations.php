<?php

namespace App\Filament\Resources\ScreenLocationResource\Pages;

use App\Filament\Resources\ScreenLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScreenLocations extends ListRecords
{
    protected static string $resource = ScreenLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
