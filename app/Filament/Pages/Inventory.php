<?php

namespace App\Filament\Pages;

use Filament\Actions;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use App\Models\InventoryManageDetail;

class Inventory extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.inventory';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->recordTitleAttribute('id')
            ->columns($this->getTableColumns())
            ->filters([
                // Add filters here if needed, e.g., date range, mode, status
            ])
            ->actions([
                // Add view/edit actions if desired
            ])
            ->bulkActions([
                // Actions\DeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return InventoryManageDetail::query()->with([
            'inventoryManage.store',
            'inventoryManage.screenLocation',
            'inventoryManage.event',
            'spare',
            'asset',
        ]);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('inventoryManage.created_at')
                ->label('Date')
                ->dateTime()
                ->sortable(),
            TextColumn::make('inventoryManage.screen')
                ->label('Mode')
                ->formatStateUsing(fn($state): string => $state ? 'Screen Mode' : 'Event Mode')
                ->sortable(),
            TextColumn::make('inventoryManage.status')
                ->label('Status')
                ->formatStateUsing(fn($state): string => $state === '1' ? 'Check In' : 'Check Out')
                ->sortable(),
            TextColumn::make('inventoryManage.store.name')
                ->label('Store')
                ->sortable(),
            TextColumn::make('inventoryManage.screenLocation.name')
                ->label('Screen Location')
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('inventoryManage.event.name')
                ->label('Event')
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('spare.name')
                ->label('Spare')
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('model')
                ->label('Spare Model')
                ->formatStateUsing(function ($record) {
                    return optional(\App\Models\SpareModel::find($record->model))->name ?? '-';
                })
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('asset.name')
                ->label('Asset')
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('asset_models_id')
                ->label('Asset Model')
                ->formatStateUsing(function ($record) {
                    return optional(\App\Models\AssetModel::find($record->asset_models_id))->name ?? '-';
                })
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('condition')
                ->label('Condition')
                ->sortable(),
            TextColumn::make('quantity')
                ->label('Quantity')
                ->sortable(),
            TextColumn::make('inventoryManage.remark')
                ->label('Remark')
                ->limit(50)
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public function getHeader(): ?View
    {
        return view('filament.pages.inventory-header'); // Optional: Create a blade file for custom header if needed
    }
}
