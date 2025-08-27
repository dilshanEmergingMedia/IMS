<?php

namespace App\Filament\Resources;

use App\Models\Inventory;
use App\Filament\Resources\InventoryResource\Pages;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Store;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\screenLocation;
use App\Models\Spare;
use App\Models\SpareModel;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationGroup = 'Screen';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Inventory Details')
                    ->description('Enter main inventory information.')
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
                        Select::make('screen_id')
                            ->label('Screen')
                            ->options(
                                screenLocation::where('status', '1')
                                    ->pluck('name', 'id') // Adjust 'name' if needed; adjust model if it's ScreenLocation
                                    ->toArray()
                            )
                            ->placeholder('Select a screen')
                            ->searchable(),
                    ])
                    ->columns(2),
                Repeater::make('inventoryDetails')
                    ->relationship('inventoryDetails') // Assumes hasMany relationship defined in Inventory model
                    ->label('Inventory Details')
                    ->schema([
                        Select::make('spare_id')
                            ->label('Spare')
                            ->required()
                            ->options(
                                Spare::where('status', '1')
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Select a spare')
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('model', null);
                            }),
                        Select::make('model')
                            ->label('Model')
                            ->required()
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
                    ->itemLabel(fn(array $state): ?string => ($state['spare_id'] ?? 'New') . ' - ' . ($state['condition'] ?? 'New') . ' (Qty: ' . ($state['qty'] ?? 0) . ')')
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
                Tables\Columns\TextColumn::make('screen.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inventoryDetails.spare.name'),
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
