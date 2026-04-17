<?php

namespace App\Filament\Admin\Clusters\SystemManagement\Resources\AdminUserResource\Pages;

use App\Filament\Admin\Clusters\SystemManagement\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminUser extends EditRecord
{
    protected static string $resource = AdminUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
