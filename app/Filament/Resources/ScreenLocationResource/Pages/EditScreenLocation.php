<?php

namespace App\Filament\Resources\ScreenLocationResource\Pages;

use App\Filament\Resources\ScreenLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScreenLocation extends EditRecord
{
    protected static string $resource = ScreenLocationResource::class;

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
