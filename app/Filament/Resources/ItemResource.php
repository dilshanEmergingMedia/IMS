<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Asset;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Item Information')
                    ->description('Basic details about the item')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder('Enter item name'),

                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->unique(Item::class, 'code', ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('e.g., ITM-001'),
                            ]),

                        Forms\Components\Select::make('assets_id')
                            ->label('Asset')
                            ->relationship('asset', 'name', fn(Builder $query) => $query->where('status', '1'))
                            ->searchable(['name'])
                            ->preload()
                            ->placeholder('Select an asset')
                            ->prefixIcon('heroicon-o-cube')
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('asset_models_id', null))
                            ->createOptionForm(null) // Disable create if not needed
                            ->helperText('Only active assets are shown'),

                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'working' => 'Working',
                                'faulty' => 'Faulty',
                                'need_to_repair' => 'Need to Repair',
                                'repairing' => 'Repairing',
                            ])
                            ->default('working')
                            ->placeholder('Select condition')
                            ->prefixIcon('heroicon-o-shield-check')
                            ->helperText('Current operational status of the item'),
                    ]),

                Section::make('Additional Details')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->collapsed() // Collapsible by default to reduce clutter
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->placeholder('Add any notes or description about this item...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::SemiBold),
                Tables\Columns\TextColumn::make('asset.name')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::SemiBold),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'working' => 'success',
                        'faulty' => 'danger',
                        'need_to_repair' => 'warning',
                        'repairing' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'working' => 'heroicon-o-check-circle',
                        'faulty' => 'heroicon-o-x-circle',
                        'need_to_repair' => 'heroicon-o-wrench',
                        'repairing' => 'heroicon-o-cog-8-tooth',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y'),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap()
                    ->default('-'),

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
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers here if needed in the future
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
