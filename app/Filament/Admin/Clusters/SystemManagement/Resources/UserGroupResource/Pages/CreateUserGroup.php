<?php

namespace App\Filament\Admin\Clusters\SystemManagement\Resources\UserGroupResource\Pages;

use App\Filament\Admin\Clusters\SystemManagement\Resources\UserGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserGroup extends CreateRecord
{
    protected static string $resource = UserGroupResource::class;
}
