<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryManageResource\Pages;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Event;
use App\Models\inventoryManage;
use App\Models\screenLocation;
use App\Models\Spare;
use App\Models\SpareModel;
use App\Models\Store;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class InventoryManageResource extends Resource
{
    protected static ?string $model = inventoryManage::class;


    protected static ?string $navigationLabel = 'Checkout/Checkin';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Mode Selection')
                    ->description('Choose the operation mode and inventory status.')
                    ->schema([
                        Toggle::make('screen')
                            ->label(fn($get) => $get('screen') ? 'Screen Mode' : 'Event Mode')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Toggle between Screen and Event modes.')
                            ->inline(false)
                            ->extraAttributes(['class' => 'mt-4'])
                            ->reactive()
                            ->onIcon('heroicon-m-tv')
                            ->offIcon('heroicon-m-calendar'),
                        ToggleButtons::make('status')
                            ->label('Inventory Status')
                            ->required()
                            ->options([
                                'check_in' => 'Check In',
                                'check_out' => 'Check Out',
                            ])
                            ->colors([
                                'check_in' => 'success',
                                'check_out' => 'danger',
                            ])
                            ->icons([
                                'check_in' => 'heroicon-o-arrow-down-circle',
                                'check_out' => 'heroicon-o-arrow-up-circle',
                            ])
                            ->inline()
                            ->default('check_in')
                            ->formatStateUsing(fn($state) => $state === '1' ? 'check_in' : 'check_out')
                            ->dehydrateStateUsing(fn($state) => $state === 'check_in' ? '1' : '0')
                            ->hint('Select whether the spare is being checked in or out.')
                            ->extraAttributes(['class' => 'mt-4'])
                            ->disabled(fn($get) => is_null($get('screen'))),
                    ])
                    ->collapsible()
                    ->icon('heroicon-o-cog')
                    ->columns(['default' => 1, 'sm' => 2])
                    ->extraAttributes(['class' => 'shadow-sm rounded-lg p-6']),

                Section::make('Spare Details')
                    ->description('Provide details for the spare part.')
                    ->schema([
                        Select::make('store_id')
                            ->label('Store')
                            ->required()
                            ->options(
                                Store::where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Select a store')
                            ->searchable()
                            ->prefixIcon('heroicon-o-building-storefront')
                            ->columnSpan(1)
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                        Select::make('screenLocation_id')
                            ->label('Screen Location')
                            ->required(fn($get) => $get('screen'))
                            ->options(
                                ScreenLocation::where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Select a screen location')
                            ->searchable()
                            ->prefixIcon('heroicon-o-map')
                            ->visible(fn($get) => $get('screen'))
                            ->columnSpan(2)
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                        Select::make('event_id')
                            ->label('Event')
                            ->required(fn($get) => !$get('screen'))
                            ->options(
                                Event::where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Select an event')
                            ->searchable()
                            ->prefixIcon('heroicon-o-calendar')
                            ->visible(fn($get) => !$get('screen'))
                            ->columnSpan(2)
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                    ])
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->extraAttributes(['class' => 'shadow-sm rounded-lg mt-6'])
                    ->disabled(fn($get) => is_null($get('screen')) || is_null($get('status'))),

                Repeater::make('inventoryManageDetails')
                    ->relationship('inventoryManageDetails')
                    ->label('Inventory Details')
                    ->schema([
                        Select::make('spare_id')
                            ->label('Spare')
                            ->required(fn($get) => $get('../../screen')) // Access parent form's screen state
                            ->options(
                                Spare::where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Select a spare')
                            ->searchable()
                            ->prefixIcon('heroicon-o-wrench-screwdriver')
                            ->visible(fn($get) => $get('../../screen'))
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('model', null);
                            })
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                        Select::make('model')
                            ->label('Model')
                            ->required(fn($get) => $get('../../screen'))
                            ->options(function (callable $get) {
                                $spareId = $get('spare_id');
                                if (!$spareId) {
                                    return [];
                                }
                                return SpareModel::where('spare_id', $spareId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->placeholder('Select a model')
                            ->searchable()
                            ->prefixIcon('heroicon-o-identification')
                            ->visible(fn($get) => $get('../../screen'))
                            ->reactive()
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                        Select::make('asset_id')
                            ->label('Asset')
                            ->required(fn($get) => !$get('../../screen'))
                            ->options(
                                Asset::where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Select an asset')
                            ->searchable()
                            ->prefixIcon('heroicon-o-cube')
                            ->visible(fn($get) => !$get('../../screen'))
                            ->reactive()
                            ->afterStateUpdated(function (callable $set) {
                                $set('asset_models_id', null);
                            })
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                        Select::make('asset_models_id')
                            ->label('Asset Model')
                            ->required(fn($get) => !$get('../../screen'))
                            ->options(function (callable $get) {
                                $assetId = $get('asset_id');
                                if (!$assetId) {
                                    return [];
                                }
                                return AssetModel::where('asset_id', $assetId)
                                    ->where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->placeholder('Select an asset model')
                            ->searchable()
                            ->prefixIcon('heroicon-o-rectangle-stack')
                            ->visible(fn($get) => !$get('../../screen'))
                            ->reactive()
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                        Select::make('condition')
                            ->label('Condition')
                            ->required()
                            ->options([
                                'working' => 'Working',
                                'faulty' => 'Faulty',
                                'need_to_repair' => 'Need to Repair',
                                'repairing' => 'Repairing',
                            ])
                            ->placeholder('Select condition')
                            ->prefixIcon('heroicon-o-shield-check')
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->placeholder('Enter quantity')
                            ->integer()
                            ->prefixIcon('heroicon-o-calculator')
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg']),
                    ])
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => ($state['spare_id'] ?? $state['asset_id'] ?? 'New') . ' - ' . ($state['condition'] ?? 'New') . ' (Qty: ' . ($state['quantity'] ?? 0) . ')')
                    ->columnSpanFull()
                    ->addActionLabel('Add Another Inventory Detail')
                    ->extraAttributes(['class' => 'shadow-sm rounded-lg'])
                    ->disabled(fn($get) => is_null($get('screen')) || is_null($get('status'))),

                Section::make('Additional Information')
                    ->description('Add any additional details or remarks.')
                    ->schema([
                        Textarea::make('remark')
                            ->label('Remarks')
                            ->placeholder('Add any additional notes or remarks')
                            ->columnSpanFull()
                            ->rows(4)
                            ->extraAttributes(['class' => 'bg-gray-50 rounded-lg resize-y']),
                    ])
                    ->columns(['default' => 1, 'sm' => 2])
                    ->collapsible()
                    ->icon('heroicon-o-information-circle')
                    ->extraAttributes(['class' => 'shadow-sm rounded-lg p-6 mt-6'])
                    ->disabled(fn($get) => is_null($get('screen')) || is_null($get('status'))),
            ])
            ->extraAttributes(['class' => 'max-w-4xl mx-auto p-6 rounded-xl']);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('screenLocation.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryManageDetails.spare.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventoryManageDetails.model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inventoryManageDetails.quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListInventoryManages::route('/'),
            'create' => Pages\CreateInventoryManage::route('/create'),
            'edit' => Pages\EditInventoryManage::route('/{record}/edit'),
        ];
    }
}
