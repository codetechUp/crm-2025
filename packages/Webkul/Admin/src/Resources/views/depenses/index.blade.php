<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.depenses.index.title')
    </x-slot>

    <v-depense>
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="depenses" />
        
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.depenses.index.title')
                    </div>
                </div>
        
                <div class="flex items-center gap-x-2.5">
                    @if (bouncer()->hasPermission('depenses.create'))
                        <a 
                            href="{{ route('admin.depenses.create') }}"
                            class="primary-button"
                        >
                            @lang('admin::app.depenses.index.create-btn')
                        </a>
                    @endif
                </div>
            </div>
        
            <x-admin::shimmer.datagrid />
        </div>
    </v-depense>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-depense-template"
        >
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex flex-col gap-2">
                        <x-admin::breadcrumbs name="depenses" />
        
                        <div class="text-xl font-bold dark:text-white">
                            @lang('admin::app.depenses.index.title')
                        </div>
                    </div>

                    <div class="flex items-center gap-x-2.5">
                        @if (bouncer()->hasPermission('depenses.create'))
                            <a 
                                href="{{ route('admin.depenses.create') }}"
                                class="primary-button"
                            >
                                @lang('admin::app.depenses.index.create-btn')
                            </a>
                        @endif
                    </div>
                </div>
            
                <x-admin::datagrid :src="route('admin.depenses.index')" />
            </div>
        </script>

        <script type="module">
            app.component('v-depense', {
                template: '#v-depense-template',
            });
        </script>
    @endPushOnce
</x-admin::layouts>