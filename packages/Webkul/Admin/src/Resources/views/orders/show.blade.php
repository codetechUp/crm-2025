<x-admin::layouts>
    <x-slot:title>
        {{ $order->order_number }} - {{ $order->subject }}
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="orders" :entity="$order" />

                <div class="text-xl font-bold dark:text-white">
                    {{ $order->order_number }} - {{ $order->subject }}
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('orders.edit'))
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="secondary-button">
                        @lang('admin::app.orders.show.edit-btn')
                    </a>
                @endif
            </div>
        </div>

        <div class="flex gap-4">
            <!-- Main Content -->
            <div class="flex-1 flex flex-col gap-4">
                <!-- Order Information -->
                <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">@lang('admin::app.orders.show.order-info')</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.order-number')</p>
                            <p class="font-medium dark:text-white">{{ $order->order_number }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.status')</p>
                            <p class="font-medium dark:text-white">{{ $order->getStatusLabel() }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.client')</p>
                            <p class="font-medium dark:text-white">
                                <a href="{{ route('admin.contacts.persons.view', $order->person_id) }}" class="text-brandColor hover:underline">
                                    {{ $order->person->name }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.commercial')</p>
                            <p class="font-medium dark:text-white">{{ $order->user->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.expected-delivery')</p>
                            <p class="font-medium dark:text-white">
                                {{ $order->expected_delivery_date ? $order->expected_delivery_date->format('d/m/Y') : '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.type')</p>
                            <p class="font-medium dark:text-white">
                                {{ $order->has_production ? trans('admin::app.orders.show.with-production') : trans('admin::app.orders.show.without-production') }}
                            </p>
                        </div>

                        @if($order->description)
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.description')</p>
                                <p class="font-medium dark:text-white">{{ $order->description }}</p>
                            </div>
                        @endif

                        @if($order->notes)
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">@lang('admin::app.orders.show.notes')</p>
                                <p class="font-medium dark:text-white">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    @if($order->isDelayed())
                        <div class="mt-4 p-3 bg-red-100 border border-red-300 rounded text-red-800">
                            <strong>⚠️ @lang('admin::app.orders.show.delayed')</strong>
                            <p>@lang('admin::app.orders.show.delayed-message', ['days' => now()->diffInDays($order->expected_delivery_date)])</p>
                        </div>
                    @elseif($order->isAtRisk())
                        <div class="mt-4 p-3 bg-yellow-100 border border-yellow-300 rounded text-yellow-800">
                            <strong>⏰ @lang('admin::app.orders.show.at-risk')</strong>
                            <p>@lang('admin::app.orders.show.at-risk-message', ['days' => now()->diffInDays($order->expected_delivery_date, false)])</p>
                        </div>
                    @endif
                </div>

                <!-- Order Products -->
                @if($order->items && $order->items->count() > 0)
                    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">@lang('admin::app.orders.show.products')</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="text-left py-2 px-3 text-sm font-semibold dark:text-white">@lang('admin::app.orders.show.product-name')</th>
                                        <th class="text-center py-2 px-3 text-sm font-semibold dark:text-white">@lang('admin::app.orders.show.quantity')</th>
                                        <th class="text-right py-2 px-3 text-sm font-semibold dark:text-white">@lang('admin::app.orders.show.price')</th>
                                        <th class="text-right py-2 px-3 text-sm font-semibold dark:text-white">@lang('admin::app.orders.show.total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <td class="py-3 px-3 dark:text-gray-300">{{ $item->product->name }}</td>
                                            <td class="py-3 px-3 text-center dark:text-gray-300">{{ $item->quantity }}</td>
                                            <td class="py-3 px-3 text-right dark:text-gray-300">{{ number_format($item->price, 2) }} </td>
                                            <td class="py-3 px-3 text-right dark:text-gray-300">{{ number_format($item->total, 2) }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                        <td colspan="3" class="py-3 px-3 text-right font-semibold dark:text-white">@lang('admin::app.orders.show.grand-total')</td>
                                        <td class="py-3 px-3 text-right font-semibold dark:text-white">{{ number_format($order->grand_total, 2) }} </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Status Timeline -->
                <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold mb-4 dark:text-white">@lang('admin::app.orders.show.status-history')</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->statusHistory as $history)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-3 h-3 bg-brandColor rounded-full"></div>
                                    @if(!$loop->last)
                                        <div class="w-0.5 h-full bg-gray-300 dark:bg-gray-600"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-4">
                                    <p class="font-medium dark:text-white">{{ trans("admin::app.orders.statuses.{$history->status}") }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $history->changed_at->format('d/m/Y H:i') }} - {{ $history->changedBy->name ?? 'Système' }}
                                    </p>
                                    @if($history->notes)
                                        <p class="text-sm mt-1 dark:text-gray-300">{{ $history->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-80 flex flex-col gap-4">
                <!-- Change Status -->
                @if (bouncer()->hasPermission('orders.update-status') && $order->status !== 'delivered')
                    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">@lang('admin::app.orders.show.change-status')</h3>
                        
                        <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                            @csrf
                            <x-admin::form.control-group class="mb-4">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.orders.show.new-status')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="status"
                                    rules="required"
                                >
                                    @foreach($order->getAvailableStatuses() as $statusKey => $statusLabel)
                                        <option value="{{ $statusKey }}" {{ $order->status === $statusKey ? 'selected' : '' }}>
                                            {{ $statusLabel }}
                                        </option>
                                    @endforeach
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="status" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="mb-4">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.orders.show.status-notes')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="notes"
                                    rows="3"
                                />
                            </x-admin::form.control-group>

                            <button type="submit" class="primary-button w-full">
                                @lang('admin::app.orders.show.update-status-btn')
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin::layouts>
