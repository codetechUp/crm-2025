<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang("admin::app.errors.{$errorCode}.title")
    </x-slot>

    <div class="flex h-[100vh] flex-col items-center justify-center gap-10">
        <div class="flex flex-col items-center gap-5">
            <!-- Logo (same as login page) -->
            @if ($logo = core()->getConfigData('general.general.admin_logo.logo_image'))
                <img
                    class="h-10 w-[110px]"
                    src="https://crm.synapsispharma.com/public/storage/configuration/fH5ZCxBbFvMJhC78U8daMrtzFwn37Ki6BhuDQjEv.jpg"
                    alt="{{ config('app.name') }}"
                />
            @else
                <img
                    class="w-max"
                    src="{{ vite()->asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                />
            @endif

            <div class="box-shadow flex min-w-[300px] max-w-[500px] flex-col rounded-md bg-white px-6 py-8 dark:bg-gray-900">
                <div class="text-center">
                    <div class="mb-4 text-5xl font-bold tabular-nums text-brandColor">
                        {{ $errorCode }}
                    </div>

                    <p class="mb-2 text-xl font-bold text-gray-800 dark:text-white">
                        @lang("admin::app.errors.{$errorCode}.title")
                    </p>

                    <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                        @lang("admin::app.errors.{$errorCode}.description")
                    </p>

                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <a
                            href="{{ url()->previous() ?? route('admin.dashboard.index') }}"
                            class="primary-button"
                        >
                            @lang('admin::app.errors.go-back')
                        </a>
                        <a
                            href="{{ route('admin.dashboard.index') }}"
                            class="secondary-button"
                        >
                            @lang('admin::app.errors.dashboard')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts.anonymous>
