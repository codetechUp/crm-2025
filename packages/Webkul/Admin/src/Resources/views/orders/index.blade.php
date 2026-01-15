<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.orders.index.title')
    </x-slot>

    <v-orders>
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="orders" />
        
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.orders.index.title')
                    </div>
                </div>
        
                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                        @if (bouncer()->hasPermission('orders.create'))
                            <a 
                                href="{{ route('admin.orders.create') }}"
                                class="primary-button"
                            >
                                @lang('admin::app.orders.index.create-btn')
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        
            <x-admin::shimmer.datagrid />
        </div>
    </v-orders>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-orders-template"
        >
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex flex-col gap-2">
                        <x-admin::breadcrumbs name="orders" />
        
                        <div class="text-xl font-bold dark:text-white">
                            @lang('admin::app.orders.index.title')
                        </div>
                    </div>

                    <div class="flex items-center gap-x-2.5">
                        <div class="flex items-center gap-x-2.5">
                            @if (bouncer()->hasPermission('orders.create'))
                                <a 
                                    href="{{ route('admin.orders.create') }}"
                                    class="primary-button"
                                >
                                    @lang('admin::app.orders.index.create-btn')
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            
                <x-admin::datagrid :src="route('admin.orders.index')" />
            </div>
        </script>

        <script type="module">
            app.component('v-orders', {
                template: '#v-orders-template',
            });
        </script>
    @endPushOnce
</x-admin::layouts>
