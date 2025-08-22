<?php

namespace App\Filament\Resources\SpareModelResource\Pages;

use App\Filament\Resources\SpareModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpareModel extends CreateRecord
{
    protected static string $resource = SpareModelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
