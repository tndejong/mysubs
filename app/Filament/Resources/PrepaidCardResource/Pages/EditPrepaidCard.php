<?php

namespace App\Filament\Resources\PrepaidCardResource\Pages;

use App\Filament\Resources\PrepaidCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrepaidCard extends EditRecord
{
    protected static string $resource = PrepaidCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
