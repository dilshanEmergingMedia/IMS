<?php

namespace App\Filament\Resources\SpareResource\Pages;

use App\Filament\Resources\SpareResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpares extends ListRecords
{
    protected static string $resource = SpareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
