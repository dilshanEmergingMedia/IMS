<?php

namespace App\Filament\Resources\AssetModelResource\Pages;

use App\Filament\Resources\AssetModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetModel extends CreateRecord
{
    protected static string $resource = AssetModelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
