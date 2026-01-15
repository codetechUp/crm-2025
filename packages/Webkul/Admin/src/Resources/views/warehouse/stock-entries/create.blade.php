<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.warehouse.stock-entries.create.title')
    </x-slot:title>

    <x-admin::form :action="route('admin.warehouse.stock_entries.store')">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.warehouse.stock-entries.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <button type="submit" class="primary-button">
                        @lang('admin::app.warehouse.stock-entries.create.save-btn')
                    </button>
                </div>
            </div>

            <div class="box-shadow flex flex-col gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-4 px-4 py-2">
                    <!-- Stock Entry Information -->
                    <div id="stock-entry-info" class="flex flex-col gap-4">
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.warehouse.stock-entries.create.general')
                            </p>

                            <p class="text-sm text-gray-600 dark:text-white">
                                @lang('admin::app.warehouse.stock-entries.create.general-info')
                            </p>
                        </div>

                        <div class="w-1/2 max-md:w-full">
                            <div class="flex gap-4">
                                <x-admin::form.control-group class="flex-1 w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.warehouse.stock-entries.create.date-appro')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="date_appro"
                                        :value="old('date_appro') ?? date('Y-m-d')"
                                        rules="required"
                                        :label="trans('admin::app.warehouse.stock-entries.create.date-appro')"
                                    />

                                    <x-admin::form.control-group.error control-name="date_appro" />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="flex-1 w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.warehouse.stock-entries.create.supplier')
                                    </x-admin::form.control-group.label>

                                    <x-admin::lookup 
                                        :src="route('admin.contacts.persons.search')"
                                        name="person_id"
                                        :label="trans('admin::app.warehouse.stock-entries.create.supplier')"
                                        :placeholder="trans('admin::app.warehouse.stock-entries.create.select-person')"
                                    />

                                    <x-admin::form.control-group.error control-name="person_id" />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.warehouse.stock-entries.create.notes')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="notes"
                                    :value="old('notes')"
                                    :label="trans('admin::app.warehouse.stock-entries.create.notes')"
                                />
                            </x-admin::form.control-group>
                        </div>
                    </div>

                    <!-- Products Section -->
                    <div id="stock-entry-products" class="flex flex-col gap-4">
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.warehouse.stock-entries.create.products')
                            </p>

                            <p class="text-sm text-gray-600 dark:text-white">
                                @lang('admin::app.warehouse.stock-entries.create.products-info')
                            </p>
                        </div>

                        <!-- Product List Vue Component -->
                        <v-stock-item-list></v-stock-item-list>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-stock-item-list-template">
            <div class="flex flex-col gap-4">
                <div class="block w-full">
                    <!-- Table -->
                    <x-admin::table>
                        <!-- Table Head -->
                        <x-admin::table.thead>
                            <x-admin::table.thead.tr>
                                <x-admin::table.th>
                                    @lang('admin::app.warehouse.stock-entries.create.product-name')
                                </x-admin::table.th>

                                <x-admin::table.th class="text-center">
                                    @lang('admin::app.warehouse.stock-entries.create.quantity')
                                </x-admin::table.th>

                                <x-admin::table.th
                                    v-if="items.length > 1"
                                    class="!px-2 ltr:text-right rtl:text-left"
                                >
                                    @lang('admin::app.warehouse.stock-entries.create.action')
                                </x-admin::table.th>
                            </x-admin::table.thead.tr>
                        </x-admin::table.thead>

                        <!-- Table Body -->
                        <x-admin::table.tbody>
                            <template v-for="(item, index) in items" :key="index">
                                <v-stock-item
                                    :item="item"
                                    :index="index"
                                    @onRemoveItem="removeItem(item)"
                                ></v-stock-item>
                            </template>
                        </x-admin::table.tbody>
                    </x-admin::table>
                </div>

                <!-- Add New Product -->
                <span
                    class="text-md flex max-w-max cursor-pointer items-center gap-2 text-brandColor"
                    @click="addItem"
                >
                    @lang('admin::app.warehouse.stock-entries.create.add-product')
                </span>
            </div>
        </script>

        <script type="text/x-template" id="v-stock-item-template">
            <x-admin::table.thead.tr>
                <!-- Product Name -->
                <x-admin::table.td>
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::lookup
                            ::src="src"
                            ::name="`${inputName}[product_id]`"
                            :placeholder="trans('admin::app.warehouse.stock-entries.create.search-products')"
                            @on-selected="productSelected"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Quantity -->
                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[quantity]`"
                            ::value="item.quantity"
                            rules="required|numeric|min_value:1"
                            :label="trans('admin::app.warehouse.stock-entries.create.quantity')"
                            :placeholder="trans('admin::app.warehouse.stock-entries.create.quantity')"
                            @on-change="(event) => item.quantity = event.value"
                            position="center"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <!-- Action -->
                <x-admin::table.td
                    v-if="$parent.items.length > 1"
                    class="!px-2 ltr:text-right rtl:text-left"
                >
                    <x-admin::form.control-group class="!mb-0">
                        <i
                            @click="removeItem"
                            class="icon-delete cursor-pointer text-2xl"
                        ></i>
                    </x-admin::form.control-group>
                </x-admin::table.td>
            </x-admin::table.thead.tr>
        </script>

        <script type="module">
            app.component('v-stock-item-list', {
                template: '#v-stock-item-list-template',

                data() {
                    return {
                        items: [
                            {
                                product_id: '',
                                quantity: 1
                            }
                        ]
                    }
                },

                methods: {
                    addItem() {
                        this.items.push({
                            product_id: '',
                            quantity: 1
                        });
                    },

                    removeItem(item) {
                        if (this.items.length > 1) {
                            let index = this.items.indexOf(item);

                            this.items.splice(index, 1);
                        }
                    }
                }
            });

            app.component('v-stock-item', {
                template: '#v-stock-item-template',

                props: ['index', 'item'],

                computed: {
                    inputName() {
                        return "items[" + this.index + "]";
                    },

                    src() {
                        return "{{ route('admin.products.search') }}";
                    }
                },

                methods: {
                    productSelected(product) {
                        this.item.product_id = product.id;
                    },

                    removeItem() {
                        this.$emit('onRemoveItem', this.item);
                    }
                }
            });
        </script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            html {
                scroll-behavior: smooth;
            }
        </style>
    @endPushOnce
</x-admin::layouts>
