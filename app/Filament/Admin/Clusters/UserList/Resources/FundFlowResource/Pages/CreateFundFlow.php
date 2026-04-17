<?php

namespace App\Filament\Admin\Clusters\UserList\Resources\FundFlowResource\Pages;

use App\Filament\Admin\Clusters\UserList\Resources\FundFlowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFundFlow extends CreateRecord
{
    protected static string $resource = FundFlowResource::class;
}
