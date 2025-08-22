<?php

namespace App\Filament\Resources\SpareModelResource\Pages;

use App\Filament\Resources\SpareModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpareModel extends EditRecord
{
    protected static string $resource = SpareModelResource::class;

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
