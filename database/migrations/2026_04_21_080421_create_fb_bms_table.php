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
        Schema::create('fb_bms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级BM ID');
            $table->unsignedBigInteger('group_id')->default(0)->comment('group ID');
            $table->unsignedBigInteger('member_id')->default(0)->comment('member ID');
            $table->unsignedBigInteger('supplier_id')->comment('BM供应商ID');

            // 建议用 string 存储，FB ID 极长，避免大整数溢出
            $table->string('business_id', 50)->comment('BUSINESS ID');
            $table->string('business_name', 100)->comment('BM名称');

            $table->integer('qty_limit')->default(1)->comment('账户数量上限');
            $table->integer('qty_available')->default(1)->comment('可用账户数量');
            $table->integer('child_bm_qty')->default(0)->comment('可创建子BM数');

            $table->string('manager_name')->nullable()->comment('BM系统用户名');
            $table->string('manager_id')->default('')->comment('BM系统用户ID');
            $table->string('manager_token')->nullable()->comment('BM系统用户口令');

            $table->string('app_id')->nullable()->comment('BM绑定的应用ID');
            $table->string('app_secret')->nullable()->comment('BM绑定的应用SECRET');

            $table->integer('open_time')->nullable()->comment('BM创建时间');
            $table->tinyInteger('status')->nullable()->comment('BM状态');
            $table->mediumInteger('archived')->default(50)->comment('是否归档');
            $table->string('remark')->nullable()->comment('备注');

            $table->timestamps();
            $table->softDeletes(); // 对应 deleted_at

            // 索引设置
            $table->index('business_id', 'bms_business_id_index');
            $table->index('archived', 'bms_archived_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fb_bms');
    }
};
