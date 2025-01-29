<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrepaidCardResource\Pages;
use App\Models\PrepaidCard;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class PrepaidCardResource extends Resource
{
    protected static ?string $model = PrepaidCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('Member')
                    ->relationship(name: 'member', titleAttribute: 'full_name')
                    ->required(),
                TextInput::make('balance')
                    ->label('Balance')
                    ->integer()
                    ->default(12)
                    ->minValue(1)
                    ->maxValue(12)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.full_name')
                    ->label('Member'),
                TextColumn::make('balance')
                    ->badge()
                    ->formatStateUsing(function ($record) {
                        return $record->balance . 'x';
                    })
                    ->label('Balance'),
                TextColumn::make('created_at')
                    ->formatStateUsing(function ($record) {
                        return $record->created_at->format('d M. Y');
                    })
                    ->label('Created At'),
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
            'index' => Pages\ListPrepaidCards::route('/'),
            'create' => Pages\CreatePrepaidCard::route('/create'),
            'edit' => Pages\EditPrepaidCard::route('/{record}/edit'),
        ];
    }
}
