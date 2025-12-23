<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.depenses.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.depenses.store')">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="depenses.create" />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.depenses.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.depenses.create.save-btn')
                    </button>
                </div>
            </div>

            <div class="box-shadow flex flex-col gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="flex w-full gap-2 border-b border-gray-200 dark:border-gray-800">
                    <a
                        href="#depense-info"
                        class="inline-block px-3 py-2.5 border-b-2 text-sm font-medium text-brandColor border-brandColor"
                    >
                        @lang('admin::app.depenses.create.depense-info')
                    </a>
                </div>

                <div class="flex flex-col gap-4 px-4 py-2">
                    <div
                        id="depense-info"
                        class="flex flex-col gap-4"
                    >
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Date -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.depenses.create.date')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="date"
                                    :value="old('date', now()->format('Y-m-d'))"
                                    rules="required"
                                    :label="trans('admin::app.depenses.create.date')"
                                />

                                <x-admin::form.control-group.error control-name="date" />
                            </x-admin::form.control-group>

                            <!-- Category -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.depenses.create.category')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="category"
                                    :value="old('category')"
                                    rules="required|max:255"
                                    :label="trans('admin::app.depenses.create.category')"
                                    :placeholder="trans('admin::app.depenses.create.category-placeholder')"
                                />

                                <x-admin::form.control-group.error control-name="category" />
                            </x-admin::form.control-group>

                            <!-- Montant -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.depenses.create.montant')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="number"
                                    name="montant"
                                    :value="old('montant')"
                                    rules="required|numeric|min:0"
                                    step="0.01"
                                    :label="trans('admin::app.depenses.create.montant')"
                                    :placeholder="trans('admin::app.depenses.create.montant-placeholder')"
                                />

                                <x-admin::form.control-group.error control-name="montant" />
                            </x-admin::form.control-group>

                            <!-- Mode de paiement -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.depenses.create.mode-paiement')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="mode_paiement"
                                    :value="old('mode_paiement')"
                                    rules="required|max:255"
                                    :label="trans('admin::app.depenses.create.mode-paiement')"
                                    :placeholder="trans('admin::app.depenses.create.mode-paiement-placeholder')"
                                />

                                <x-admin::form.control-group.error control-name="mode_paiement" />
                            </x-admin::form.control-group>
                        </div>

                        <!-- Description -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.depenses.create.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                
                                :label="trans('admin::app.depenses.create.description')"
                                :placeholder="trans('admin::app.depenses.create.description-placeholder')"
                                rows="3"
                            />

                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>

                        <!-- Note -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.depenses.create.note')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="note"
                                :value="old('note')"
                                
                                :label="trans('admin::app.depenses.create.note')"
                                :placeholder="trans('admin::app.depenses.create.note-placeholder')"
                                rows="3"
                            />

                            <x-admin::form.control-group.error control-name="note" />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>