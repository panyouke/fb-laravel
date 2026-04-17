<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = '工作台';

    protected static ?string $navigationLabel = '工作台';

    protected static string $view = 'filament.admin.pages.dashboard';

    protected static ?string $slug = '/';

    public static function getNavigationSort(): ?int
    {
        return -1;
    }
}
