<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($this->getItems() as $item)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm min-h-[140px]">
                    <div class="text-sm font-medium text-gray-500">
                        {{ $item['label'] }}
                    </div>

                    <div class="mt-6 text-right text-3xl font-bold text-gray-800">
                        {{ $item['value'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
