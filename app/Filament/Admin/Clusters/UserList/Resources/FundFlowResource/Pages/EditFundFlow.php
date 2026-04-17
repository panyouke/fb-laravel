<?php

namespace App\Filament\Admin\Clusters\UserList\Resources\FundFlowResource\Pages;

use App\Filament\Admin\Clusters\UserList\Resources\FundFlowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFundFlow extends EditRecord
{
    protected static string $resource = FundFlowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
