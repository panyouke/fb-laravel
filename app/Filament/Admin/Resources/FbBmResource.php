<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FbBmResource\Pages;
use App\Filament\Admin\Resources\FbBmResource\RelationManagers;
use App\Models\FbBms;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class FbBmResource extends Resource
{
    protected static ?string $model = FbBms::class;

    protected static ?string $navigationLabel = 'BM列表';
    protected static ?string $navigationGroup = 'BM管理';
    protected static ?string $pluralModelLabel = 'BM列表';
    protected static ?string $modelLabel = 'BM';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('supplier_id')
                    ->label('供应商')
                    ->options([
                        '1' => '自研',
                        '2' => '论坛',
                        '3' => '其他',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('business_id')
                    ->label('BM编号')
                    ->required()
                    ->regex('/^[a-zA-Z0-9]+$/')
                    ->validationMessages([
                        'regex' => ':attribute 格式不正确，只能包含字母和数字。',
                    ]),

                TextInput::make('business_name')
                    ->label('BM名字')
                    ->required(),

                TextInput::make('manager_name')
                    ->label('BM系统用户名')
                    ->required(),

                TextInput::make('manager_name')
                    ->label('BM系统用户口令')
                    ->required(),

                TextInput::make('app_id')
                    ->label('应用ID')
                    ->required(),

                TextInput::make('app_secret')
                    ->label('应用密钥')
                    ->required(),

                TextInput::make('qty_limit')
                    ->label('最大开户数')
                    ->required(),

                TextInput::make('qty_available')
                    ->label('可创建账户数')
                    ->required(),
                Radio::make('child_bm_qty')
                    ->label('同步创建广告账户')
                    ->options([
                        1 => '创建',
                        0 => '不创建',
                    ])
                    ->default(0)
                    ->inline()
                    ->inlineLabel(false),
                TextInput::make('child_bm_qty')
                    ->label('可创建子BM数')
                    ->required(),
                TextInput::make('child_bm_qty')
                    ->label('同步创建子BM')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('supplier_id')
                    ->label('供应商')
                    ->formatStateUsing(fn ($state) => match ((string) $state) {
                        '1' => '自研',
                        '2' => '论坛',
                        '3' => '其他',
                        default => '-',
                    }),

                TextColumn::make('bm_info')
                    ->label('BM信息')
                    ->getStateUsing(function ($record) {
                        $businessId = e($record->business_id ?? '');
                        $businessName = e($record->business_name ?? '');
                        $parentBusinessId = e($record->parent?->business_id ?? '0');

                        return "
                        <div style='text-align: center; line-height: 1.6;'>
                            <div>{$businessId}</div>
                            <div>【{$businessName}】</div>
                            <div>
                                {$parentBusinessId}
                                <span style='color: #2563eb; font-weight: 700;'>母</span>
                            </div>
                        </div>
                        ";
                    })
                    ->html(),

                TextColumn::make('child_bm_qty')
                    ->label('子BM')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
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
            'index' => Pages\ListFbBms::route('/'),
//            'create' => Pages\CreateFbBm::route('/create'),
//            'edit' => Pages\EditFbBm::route('/{record}/edit'),
        ];
    }
}
