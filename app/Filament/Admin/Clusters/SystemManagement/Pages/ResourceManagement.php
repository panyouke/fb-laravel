<?php

namespace App\Filament\Admin\Clusters\SystemManagement\Pages;

use App\Filament\Admin\Clusters\SystemManagement;
use Filament\Pages\Page;

class ResourceManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.clusters.system-management.pages.resource-management';

    protected static ?string $cluster = SystemManagement::class;
}
