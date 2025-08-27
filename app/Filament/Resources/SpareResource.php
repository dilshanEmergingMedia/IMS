<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpareResource\Pages;
use App\Filament\Resources\SpareResource\RelationManagers;
use App\Models\Spare;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpareResource extends Resource
{
    protected static ?string $model = Spare::class;

    protected static ?string $navigationGroup = 'Screen';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\RichEditor::make('description')
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
            'index' => Pages\ListSpares::route('/'),
            'create' => Pages\CreateSpare::route('/create'),
            'edit' => Pages\EditSpare::route('/{record}/edit'),
        ];
    }
}
