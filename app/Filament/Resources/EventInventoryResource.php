<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventInventoryResource\Pages;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Event;
use App\Models\EventInventory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Store;
use App\Models\screenLocation;
use App\Models\Spare;
use App\Models\SpareModel;

class EventInventoryResource extends Resource
{
    protected static ?string $model = EventInventory::class;

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Event Inventory Details')
                    ->description('Enter event inventory information.')
                    ->schema([
                        Select::make('store_id')
                            ->label('Store')
                            ->required()
                            ->options(
                                Store::where('status', '1')
                                    ->pluck('name', 'id') // Adjust 'name' if needed
                                    ->toArray()
                            )
                            ->placeholder('Select a store')
                            ->searchable(),
                        Select::make('event_id')
                            ->label('Event')
                            ->options(
                                Event::where('status', '1')
                                    ->pluck('name', 'id') // Adjust 'name' if needed; adjust model if it's Event
                                    ->toArray()
                            )
                            ->placeholder('Select an event')
                            ->searchable(),
                    ])
                    ->columns(2),
                Repeater::make('inventoryDetails')
                    ->relationship('inventoryDetails') // Assumes hasMany relationship defined in Inventory model
                    ->label('Inventory Details')
                    ->schema([
                        Select::make('asset_id')
                            ->label('Asset')
                            ->required()
                            ->options(
                                Asset::where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Select an asset')
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('model', null);
                            }),
                        Select::make('model')
                            ->label('Model')
                            ->required()
                            ->options(function (callable $get) {
                                $assetId = $get('asset_id');
                                if (!$assetId) {
                                    return [];
                                }
                                return AssetModel::where('asset_id', $assetId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->placeholder('Select a model')
                            ->searchable()
                            ->reactive(),
                        Select::make('condition')
                            ->label('Condition')
                            ->required()
                            ->options([
                                'working' => 'Working',
                                'faulty' => 'Faulty',
                                'need_to_repair' => 'Need to Repair',
                                'repairing' => 'Repairing',
                            ])
                            ->placeholder('Select condition'),
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->placeholder('Enter quantity')
                            ->integer(),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => ($state['asset_id'] ?? 'New') . ' - ' . ($state['condition'] ?? 'New') . ' (Qty: ' . ($state['qty'] ?? 0) . ')')
                    ->columnSpanFull()
                    ->addActionLabel('Add Another Detail'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store.name')
                    ->numeric(),
                Tables\Columns\TextColumn::make('event.name')
                    ->default('-'),
                Tables\Columns\TextColumn::make('inventoryDetails.asset.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventInventories::route('/'),
            'create' => Pages\CreateEventInventory::route('/create'),
            'edit' => Pages\EditEventInventory::route('/{record}/edit'),
        ];
    }
}
