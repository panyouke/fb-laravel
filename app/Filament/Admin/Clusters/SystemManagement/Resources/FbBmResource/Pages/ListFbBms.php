<?php

namespace App\Filament\Admin\Clusters\SystemManagement\Resources\FbBmResource\Pages;

use App\Filament\Admin\Clusters\SystemManagement\Resources\FbBmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFbBms extends ListRecords
{
    protected static string $resource = FbBmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
