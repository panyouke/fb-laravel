<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">用户数</div>
                    <div class="text-3xl text-cyan-400">👥</div>
                </div>
                <div class="mt-4 text-right text-3xl font-bold text-gray-700">19</div>
            </div>

            <div class="rounded bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">总支付金额</div>
                    <div class="text-3xl text-blue-400">💰</div>
                </div>
                <div class="mt-4 text-right text-3xl font-bold text-gray-700">10,691</div>
            </div>

            <div class="rounded bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">总提现金额</div>
                    <div class="text-3xl text-rose-400">¥</div>
                </div>
                <div class="mt-4 text-right text-3xl font-bold text-gray-700">4,710</div>
            </div>

            <div class="rounded bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">用户总收入</div>
                    <div class="text-3xl text-emerald-400">＋</div>
                </div>
                <div class="mt-4 text-right text-3xl font-bold text-gray-700">69,014</div>
            </div>
        </div>

        <div class="rounded bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <input
                    type="text"
                    value="2026-04-16 00:00:00"
                    class="rounded border border-gray-300 px-4 py-2 text-sm"
                >
                <span>-</span>
                <input
                    type="text"
                    value="2026-04-16 23:59:59"
                    class="rounded border border-gray-300 px-4 py-2 text-sm"
                >
                <button
                    class="rounded bg-blue-500 px-5 py-2 text-sm text-white hover:bg-blue-600"
                    type="button"
                >
                    搜索
                </button>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="min-h-[140px] rounded bg-gray-50 p-6">
                    <div class="text-sm text-gray-500">充值金额</div>
                    <div class="mt-10 text-right text-3xl font-bold text-gray-700">0</div>
                </div>

                <div class="min-h-[140px] rounded bg-gray-50 p-6">
                    <div class="text-sm text-gray-500">提现金额</div>
                    <div class="mt-10 text-right text-3xl font-bold text-gray-700">0</div>
                </div>

                <div class="min-h-[140px] rounded bg-gray-50 p-6">
                    <div class="text-sm text-gray-500">提现人数</div>
                    <div class="mt-10 text-right text-3xl font-bold text-gray-700">0</div>
                </div>

                <div class="min-h-[140px] rounded bg-gray-50 p-6">
                    <div class="text-sm text-gray-500">首充人数</div>
                    <div class="mt-10 text-right text-3xl font-bold text-gray-700">0</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
