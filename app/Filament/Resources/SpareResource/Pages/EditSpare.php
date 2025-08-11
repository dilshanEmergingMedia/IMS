<?php

namespace App\Filament\Resources\SpareResource\Pages;

use App\Filament\Resources\SpareResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpare extends EditRecord
{
    protected static string $resource = SpareResource::class;

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
