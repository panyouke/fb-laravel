<?php

namespace App\Filament\Admin\Clusters\UserList\Resources;

use App\Filament\Admin\Clusters\UserList;
use App\Filament\Admin\Clusters\UserList\Resources\FundFlowResource\Pages;
use App\Filament\Admin\Clusters\UserList\Resources\FundFlowResource\RelationManagers;
use App\Models\FundFlow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FundFlowResource extends Resource
{
    protected static ?string $model = FundFlow::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = UserList::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFundFlows::route('/'),
            'create' => Pages\CreateFundFlow::route('/create'),
            'edit' => Pages\EditFundFlow::route('/{record}/edit'),
        ];
    }
}
