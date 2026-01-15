<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.orders.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.orders.store')">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="orders.create" />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.orders.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <button type="submit" class="primary-button">
                        @lang('admin::app.orders.create.save-btn')
                    </button>
                </div>
            </div>

            <v-order :errors="errors"></v-order>
        </div>
    </x-admin::form>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-order-template">
            <div class="box-shadow flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="w-1/2 max-md:w-full">
                    <x-admin::form.control-group class="mb-4">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.orders.create.subject')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="subject"
                            :value="old('subject')"
                            rules="required"
                            :label="trans('admin::app.orders.create.subject')"
                        />

                        <x-admin::form.control-group.error control-name="subject" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group class="mb-4">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.orders.create.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description')"
                        />
                    </x-admin::form.control-group>

                    <div class="flex gap-4 mb-4">
                        <x-admin::form.control-group class="flex-1">
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.orders.create.person')
                            </x-admin::form.control-group.label>

                            <x-admin::lookup
                                :src="route('admin.contacts.persons.search')"
                                name="person_id"
                                placeholder="@lang('admin::app.orders.create.select-person')"
                            />

                            <x-admin::form.control-group.error control-name="person_id" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group class="flex-1">
                            <x-admin::form.control-group.label>
                                @lang('admin::app.orders.create.expected-delivery')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="date"
                                name="expected_delivery_date"
                                :value="old('expected_delivery_date')"
                            />
                        </x-admin::form.control-group>
                    </div>

                    <x-admin::form.control-group class="mb-4">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.orders.create.has-production')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="switch"
                            name="has_production"
                            value="1"
                        />
                    </x-admin::form.control-group>

                    <!-- Products Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2 dark:text-white">
                            @lang('admin::app.orders.create.products')
                        </label>
                        
                        <v-order-item-list :errors="errors"></v-order-item-list>
                    </div>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.orders.create.notes')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="notes"
                            :value="old('notes')"
                        />
                    </x-admin::form.control-group>
                </div>
            </div>
        </script>

        <script type="text/x-template" id="v-order-item-list-template">
            <div class="flex flex-col gap-4">
                <div class="block w-full">
                    <x-admin::table>
                        <x-admin::table.thead>
                            <x-admin::table.thead.tr>
                                <x-admin::table.th>@lang('admin::app.orders.create.product-name')</x-admin::table.th>
                                <x-admin::table.th class="text-center">@lang('admin::app.orders.create.quantity')</x-admin::table.th>
                                <x-admin::table.th class="text-center">@lang('admin::app.orders.create.price')</x-admin::table.th>
                                <x-admin::table.th class="text-center">@lang('admin::app.orders.create.total')</x-admin::table.th>
                                <x-admin::table.th v-if="products.length > 1" class="!px-2 ltr:text-right rtl:text-left">@lang('admin::app.orders.create.action')</x-admin::table.th>
                            </x-admin::table.thead.tr>
                        </x-admin::table.thead>

                        <x-admin::table.tbody>
                            <template v-for='(product, index) in products' :key="index">
                                <v-order-item
                                    :product="product"
                                    :index="index"
                                    :errors="errors"
                                    @onRemoveProduct="removeProduct($event)"
                                ></v-order-item>
                            </template>
                        </x-admin::table.tbody>
                    </x-admin::table>
                </div>

                <span class="text-md flex max-w-max cursor-pointer items-center gap-2 text-brandColor" @click="addProduct">
                    + @lang('admin::app.orders.create.add-product')
                </span>

                <div class="flex justify-end">
                    <div class="grid w-[348px] gap-4 rounded-lg bg-gray-100 p-4 text-sm dark:bg-gray-950 dark:text-white">
                        <div class="flex w-full justify-between gap-x-5">
                            @lang('admin::app.orders.create.grand-total')
                            <input type="hidden" name="grand_total" :value="grandTotal">
                            <input type="hidden" name="sub_total" :value="subTotal">
                            <p>@{{ formatPrice(grandTotal) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="text/x-template" id="v-order-item-template">
            <x-admin::table.thead.tr>
                <x-admin::table.td>
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::lookup
                            ::src="src"
                            ::name="`${inputName}[product_id]`"
                            ::params="params"
                            ::value="{ id: product.product_id, name: product.name }"
                            @on-selected="(product) => addProduct(product)"
                            :placeholder="trans('admin::app.orders.create.search-products')"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[quantity]`"
                            ::value="product.quantity"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.orders.create.quantity')"
                            :placeholder="trans('admin::app.orders.create.quantity')"
                            @on-change="(event) => product.quantity = event.value"
                            position="center"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[price]`"
                            ::value="product.price"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.orders.create.price')"
                            :placeholder="trans('admin::app.orders.create.price')"
                            @on-change="(event) => product.price = event.value"
                            position="center"
                            ::value-label="$admin.formatPrice(product.price)"
                        />
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <x-admin::table.td class="!px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.control
                            type="inline"
                            ::name="`${inputName}[total]`"
                            ::value="product.price * product.quantity"
                            rules="required|decimal:4"
                            ::errors="errors"
                            :label="trans('admin::app.orders.create.total')"
                            :placeholder="trans('admin::app.orders.create.total')"
                            :allowEdit="false"
                            position="center"
                            ::value-label="$admin.formatPrice(product.price * product.quantity)"
                        />
                        <input type="hidden" ::name="`${inputName}[name]`" ::value="product.name" />
                    </x-admin::form.control-group>
                </x-admin::table.td>

                <x-admin::table.td v-if="$parent.products.length > 1" class="!p-2 !px-2 ltr:text-right rtl:text-left">
                    <x-admin::form.control-group class="!mb-0">
                        <i @click="removeProduct" class="icon-delete cursor-pointer text-2xl"></i>
                    </x-admin::form.control-group>
                </x-admin::table.td>
            </x-admin::table.thead.tr>
        </script>

        <script type="module">
            app.component('v-order', {
                template: '#v-order-template',
                props: ['errors'],
            });

            app.component('v-order-item-list', {
                template: '#v-order-item-list-template',
                props: ['errors'],
                data() {
                    return {
                        products: [{
                            id: null,
                            product_id: null,
                            name: '',
                            quantity: 1,
                            price: 0,
                            total: 0,
                        }],
                    }
                },
                computed: {
                    subTotal() {
                        let total = 0;
                        this.products.forEach(product => {
                            total += parseFloat(product.price * product.quantity);
                        });
                        return total;
                    },
                    grandTotal() {
                        return this.subTotal;
                    },
                },
                methods: {
                    addProduct() {
                        this.products.push({
                            id: null,
                            product_id: null,
                            name: '',
                            quantity: 1,
                            price: 0,
                            total: 0,
                        });
                    },
                    removeProduct(product) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                if (this.products.length === 1) {
                                    this.products = [{
                                        id: null,
                                        product_id: null,
                                        name: '',
                                        quantity: 1,
                                        price: 0,
                                        total: 0,
                                    }];
                                } else {
                                    const index = this.products.indexOf(product);
                                    if (index !== -1) {
                                        this.products.splice(index, 1);
                                    }
                                }
                            },
                        });
                    },
                    formatPrice(price) {
                        return new Intl.NumberFormat('fr-FR', {
                            style: 'currency',
                            currency: 'XOF'
                        }).format(price);
                    },
                },
            });

            app.component('v-order-item', {
                template: '#v-order-item-template',
                props: ['index', 'product', 'errors'],
                computed: {
                    inputName() {
                        if (this.product.id) {
                            return "items[" + this.product.id + "]";
                        }
                        return "items[item_" + this.index + "]";
                    },
                    src() {
                        return "{{ route('admin.products.search') }}";
                    },
                    params() {
                        return {
                            params: {
                                query: this.product.name,
                            },
                        };
                    },
                },
                methods: {
                    addProduct(result) {
                        this.product.product_id = result.id;
                        this.product.name = result.name;
                        this.product.price = result.price ?? 0;
                        this.product.quantity = result.quantity ?? 1;
                    },
                    removeProduct() {
                        this.$emit('onRemoveProduct', this.product);
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
