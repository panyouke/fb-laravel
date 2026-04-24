<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');

            $table->unsignedInteger('parent_id')
                ->nullable(true)
                ->default(0)
                ->comment('上级菜单');

            $table->string('name', 30)
                ->nullable(true)
                ->comment('菜单名称');

            $table->string('icon', 30)
                ->nullable(true)
                ->comment('菜单图标');

            $table->unsignedTinyInteger('type')
                ->nullable(true)
                ->default(1)
                ->comment('菜单类型: 1=菜单;2=按钮');

            $table->string('path', 30)
                ->nullable(true)
                ->comment('前端路由');

            $table->string('component', 50)
                ->nullable(true)
                ->comment('前端组件名');

            $table->unsignedTinyInteger('left_show')
                ->nullable(true)
                ->default(1)
                ->comment('是否左侧显示');

            $table->unsignedTinyInteger('top_show')
                ->nullable(true)
                ->default(0)
                ->comment('是否首页显示');

            $table->unsignedTinyInteger('target')
                ->nullable(true)
                ->default(1)
                ->comment('页面打开方式: 1=_self;2=_blank');

            $table->unsignedTinyInteger('status')
                ->nullable(true)
                ->default(1)
                ->comment('状态: 1=正常;2=禁用');

            $table->unsignedInteger('sort')
                ->nullable(true)
                ->default(0)
                ->comment('排序');

            $table->unsignedTinyInteger('is_system')
                ->nullable(true)
                ->default(0)
                ->comment('是否系统菜单(不允许删除和修改)');

            $table->index('status', 'admin_menu_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_menus');
    }
};
