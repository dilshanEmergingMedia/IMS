<?php

namespace App\Filament\Resources\ScreenLocationResource\Pages;

use App\Filament\Resources\ScreenLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateScreenLocation extends CreateRecord
{
    protected static string $resource = ScreenLocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
