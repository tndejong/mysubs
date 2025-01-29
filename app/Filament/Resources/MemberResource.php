<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'organisations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')->label('First name')->required(),
                TextInput::make('last_name')->label('Last name')->required(),
                TextInput::make('email')->label('Email')->required(),
                TextInput::make('phone_number')->label('Phonenumber')->tel(),
                DatePicker::make('date_of_birth')->label('Date of birth')->required(),
                Grid::make(['md' => 3])->schema([
                    TextInput::make('postcode')->required(),
                    TextInput::make('street')->required(),
                    TextInput::make('city')->required(),
                    Select::make('country')->options([
                        'nl' => 'Netherlands',
                        'be' => 'Belgium',
                        'de' => 'Germany',
                        'fr' => 'France'
                    ])->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->label('Name'),
                TextColumn::make('email'),
                TextColumn::make('phone_number'),
                TextColumn::make('date_of_birth')->formatStateUsing(function ($record) {
                    return Carbon::parse($record->date_of_birth)->format('d M. Y');
                }),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
