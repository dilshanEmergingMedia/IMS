<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryManageResource\Pages;
use App\Models\InventoryManage;
use App\Models\screenLocation;
use App\Models\Spare;
use App\Models\Store;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;

class InventoryManageResource extends Resource
{
    protected static ?string $model = InventoryManage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Spare Details')
                    ->description('Select details for the spare part.')
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
                            ->searchable(),
                        Select::make('screenLocation_id')
                            ->label('Screen Location')
                            ->required()
                            ->options(
                                screenLocation::where('status', '1')
                                    ->pluck('name', 'id') // Adjust 'name' to the appropriate column
                                    ->toArray()
                            )
                            ->placeholder('Select a screen location')
                            ->searchable(),
                        Select::make('spare_id')
                            ->label('Spare')
                            ->required()
                            ->options(
                                Spare::where('status', '1')
                                    ->pluck('name', 'id') // Adjust 'name' to the appropriate column
                                    ->toArray()
                            )
                            ->placeholder('Select a spare')
                            ->searchable(),
                    ])
                    ->columns(3), // Three-column layout
                Section::make('Spare Information')
                    ->schema([
                        TextInput::make('model')
                            ->label('Model')
                            ->maxLength(255)
                            ->placeholder('Enter model name'),
                        TextInput::make('serial_number')
                            ->label('Serial Number')
                            ->maxLength(255)
                            ->placeholder('Enter serial number'),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->placeholder('Enter quantity')
                            ->rule('integer'),
                    ])
                    ->columns(3),
                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('remark')
                            ->label('Remarks')
                            ->placeholder('Add any additional notes or remarks')
                            ->columnSpanFull()
                            ->rows(4),
                        ToggleButtons::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'check_in' => 'Check In',
                                'check_out' => 'Check Out',
                            ])
                            ->colors([
                                'check_in' => 'success',
                                'check_out' => 'danger',
                            ])
                            ->inline()
                            ->default('check_in')
                            ->formatStateUsing(fn($state) => $state === '1' ? 'check_in' : 'check_out')
                            ->dehydrateStateUsing(fn($state) => $state === 'check_in' ? '1' : '0')
                            ->hint('Select whether the spare is being checked in or out.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('screenLocation_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('spare_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
