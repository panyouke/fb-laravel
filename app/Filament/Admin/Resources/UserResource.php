<?php

namespace App\Filament\Admin\Resources;

//use App\Filament\Admin\Clusters\UserList;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

//    protected static ?string $cluster = UserList::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = '账号管理';

    protected static ?string $navigationLabel = '用户列表';

    protected static ?string $modelLabel = '用户';

    protected static ?string $pluralModelLabel = '用户';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('用户名')
                    ->required(),

                TextInput::make('email')
                    ->label('邮箱')
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->label('密码')
                    ->password()
                    ->required()
                    ->dehydrated(fn ($state) => filled($state)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('用户名')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('邮箱')
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
                Tables\Actions\EditAction::make()->label('编辑'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
