<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class SystemManagement extends Cluster
{
    protected static ?string $navigationLabel = '系统管理';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 50;
}
