<?php

namespace App\Filament\Resources\SpareResource\Pages;

use App\Filament\Resources\SpareResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpare extends CreateRecord
{
    protected static string $resource = SpareResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
