<?php

namespace App\Filament\Admin\Clusters\SystemManagement\Resources\AdminUserResource\Pages;

use App\Filament\Admin\Clusters\SystemManagement\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminUsers extends ListRecords
{
    protected static string $resource = AdminUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
