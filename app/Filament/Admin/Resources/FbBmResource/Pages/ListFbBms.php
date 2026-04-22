<?php

namespace App\Filament\Admin\Resources\FbBmResource\Pages;

use App\Filament\Admin\Resources\FbBmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFbBms extends ListRecords
{
    protected static string $resource = FbBmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('新增BM')
                ->createAnother(false) // 禁用“创建并新增另一个”按钮
                ->modalWidth('md'),
        ];
    }
}
