<?php

namespace App\Filament\Admin\Clusters\SystemManagement\Resources\UserGroupResource\Pages;

use App\Filament\Admin\Clusters\SystemManagement\Resources\UserGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserGroup extends EditRecord
{
    protected static string $resource = UserGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
