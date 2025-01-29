<?php

namespace App\Filament\Resources\PrepaidCardResource\Pages;

use App\Filament\Resources\PrepaidCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrepaidCards extends ListRecords
{
    protected static string $resource = PrepaidCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
