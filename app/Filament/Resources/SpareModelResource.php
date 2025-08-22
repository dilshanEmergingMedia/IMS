<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpareModelResource\Pages;
use App\Filament\Resources\SpareModelResource\RelationManagers;
use App\Models\Spare;
use App\Models\SpareModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpareModelResource extends Resource
{
    protected static ?string $model = SpareModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('spare_id')
                        ->label('Spare')
                        ->required()
                        ->options(
                            Spare::where('status', '1')
                                ->pluck('name', 'id')
                                ->toArray()
                        )
                        ->placeholder('Select spare'),
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
                        ->placeholder('Enter Store Remark')
                        ->helperText('Optional remark for the store')
                        ->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->default(true)
                            ->helperText('Active or Inactive Store'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('spare.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
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
            'index' => Pages\ListSpareModels::route('/'),
            'create' => Pages\CreateSpareModel::route('/create'),
            'edit' => Pages\EditSpareModel::route('/{record}/edit'),
        ];
    }
}
