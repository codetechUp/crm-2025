<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.products.categories.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-x-2.5">
                    <x-admin::breadcrumbs name="products" />
                </div>

                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.products.categories.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <!-- Create button for Category -->
                <div class="flex items-center gap-x-2.5">
                    <a
                        href="{{ route('admin.products.categories.create') }}"
                        class="primary-button"
                    >
                        @lang('admin::app.products.categories.index.create-btn')
                    </a>
                </div>
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.products.categories.index')" />
    </div>
</x-admin::layouts>
