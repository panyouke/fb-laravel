<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class UserList extends Cluster
{
    protected static ?string $navigationLabel = '用户列表';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;
}
