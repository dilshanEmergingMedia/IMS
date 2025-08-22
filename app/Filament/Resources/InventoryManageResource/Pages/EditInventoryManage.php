<?php

namespace App\Filament\Resources\InventoryManageResource\Pages;

use App\Filament\Resources\InventoryManageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryManage extends EditRecord
{
    protected static string $resource = InventoryManageResource::class;

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
