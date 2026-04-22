<?php

namespace App\Filament\Admin\Resources\FbBmResource\Pages;

use App\Filament\Admin\Resources\FbBmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFbBm extends EditRecord
{
    protected static string $resource = FbBmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
