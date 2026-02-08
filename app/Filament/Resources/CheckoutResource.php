<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckoutResource\Pages;
use App\Filament\Resources\CheckoutResource\RelationManagers;
use App\Models\Checkout;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CheckoutResource extends Resource
{
    protected static ?string $model = Checkout::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Checkout Details')
                    ->description('Record item checkout information')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('user_name')
                                    ->required()
                                    ->label('User Name')
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder('Enter User name'),
                                Forms\Components\Select::make('code')
                                    ->required()
                                    ->label('Item Code')
                                    ->options(
                                        \App\Models\Item::pluck('code', 'code')->toArray() // key = value = code
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select an item code')
                                    ->prefixIcon('heroicon-o-cube'),
                                DateTimePicker::make('checkout_date')
                                    ->required()
                                    ->default(now())
                                    ->placeholder('Select checkout date'),
                                DateTimePicker::make('return_date')
                                    ->placeholder('Select return date (if returned)'),

                                Forms\Components\Select::make('status')
                                    ->required()
                                    ->options([
                                        'checked_out' => 'Checked Out',
                                        'returned' => 'Returned',
                                        'overdue' => 'Overdue',
                                    ])
                                    ->default('checked_out')
                                    ->placeholder('Select status'),
                            ]),

                        Forms\Components\Textarea::make('notes')
                            ->rows(4)
                            ->placeholder('Add any notes...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('checkout_date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('return_date')
                    ->wrap()
                    ->default('-'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'checked_out' => 'success',
                        'returned' => 'info',
                        'overdue' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'checked_out' => 'heroicon-o-check-circle',
                        'returned' => 'heroicon-o-x-circle',
                        'overdue' => 'heroicon-o-wrench',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Checked Out At')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListCheckouts::route('/'),
            'create' => Pages\CreateCheckout::route('/create'),
            'edit' => Pages\EditCheckout::route('/{record}/edit'),
        ];
    }
}
