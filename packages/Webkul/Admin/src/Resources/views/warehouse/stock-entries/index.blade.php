<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.warehouse.stock-entries.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.warehouse.stock-entries.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <a href="{{ route('admin.warehouse.stock_entries.create') }}" class="primary-button">
                @lang('admin::app.warehouse.stock-entries.index.create-btn')
            </a>
        </div>
    </div>

    <div class="mt-5">
        <x-admin::datagrid :src="route('admin.warehouse.stock_entries.index')"></x-admin::datagrid>
    </div>
</x-admin::layouts>
