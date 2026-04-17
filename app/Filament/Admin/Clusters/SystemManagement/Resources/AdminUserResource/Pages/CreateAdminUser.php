<?php

namespace App\Filament\Admin\Clusters\SystemManagement\Resources\AdminUserResource\Pages;

use App\Filament\Admin\Clusters\SystemManagement\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminUser extends CreateRecord
{
    protected static string $resource = AdminUserResource::class;
}
