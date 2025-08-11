<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreenLocationResource\Pages;
use App\Filament\Resources\ScreenLocationResource\RelationManagers;
use App\Models\ScreenLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScreenLocationResource extends Resource
{
    protected static ?string $model = ScreenLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
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
                        ->placeholder('Enter Screen Remark')
                        ->helperText('Optional remark for the screen')
                        ->columnSpan(1),
                ])->columns(2),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->default(true)
                            ->helperText('Active or Inactive Screen Location'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListScreenLocations::route('/'),
            'create' => Pages\CreateScreenLocation::route('/create'),
            'edit' => Pages\EditScreenLocation::route('/{record}/edit'),
        ];
    }
}
