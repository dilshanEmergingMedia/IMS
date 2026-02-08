<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetModelResource\Pages;
use App\Models\Asset;
use App\Models\AssetModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetModelResource extends Resource
{
    protected static ?string $model = AssetModel::class;

    protected static ?string $navigationGroup = 'Event Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('asset_id')
                        ->label('Asset')
                        ->required()
                        ->options(
                            Asset::where('status', '1')
                                ->pluck('name', 'id')
                                ->toArray()
                        )
                        ->placeholder('Select asset'),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\RichEditor::make('remark')
                        ->toolbarButtons([
                            'blockquote',
                            'bold',
                            'codeBlock',
                            'h3',
                            'h4',
                            'italic',
                            'link',
                            'orderedList',
                            'strike',
                            'underline',
                        ])
                        ->placeholder('Enter asset Remark')
                        ->helperText('Optional remark for the asset')
                        ->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->default(true)
                            ->helperText('Active or Inactive asset'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.name'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Model Name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            'index' => Pages\ListAssetModels::route('/'),
            'create' => Pages\CreateAssetModel::route('/create'),
            'edit' => Pages\EditAssetModel::route('/{record}/edit'),
        ];
    }
}
