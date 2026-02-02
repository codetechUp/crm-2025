<x-admin::layouts>
    <x-slot:title>
        Factures
    </x-slot>

    <v-facture>
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <!-- Bredcrumbs -->
                    <x-admin::breadcrumbs name="quotes" />
        
                    <div class="text-xl font-bold dark:text-white">
                        Factures
                    </div>
                </div>
        
                <div class="flex items-center gap-x-2.5">
                    <!-- Create button for Facture -->
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.quotes.index.create_button.before') !!}
                        
                        @if (bouncer()->hasPermission('quotes.create'))
                            <a 
                                href="{{ route('admin.factures.create') }}"
                                class="primary-button"
                            >
                                Créer une facture
                            </a>
                        @endif
        
                        {!! view_render_event('admin.quotes.index.create_button.after') !!}
                    </div>
                </div>
            </div>
        
            <!-- DataGrid Shimmer -->
            <x-admin::shimmer.datagrid />
        </div>
    </v-facture>

    <!-- Facture Relance Popup Component -->
    <v-facture-relance ref="factureRelanceComponent"></v-facture-relance>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-facture-template"
        >
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex flex-col gap-2">
                        <!-- Bredcrumbs -->
                        <x-admin::breadcrumbs name="quotes" />
        
                        <div class="text-xl font-bold dark:text-white">
                            Factures
                        </div>
                    </div>

                    <div class="flex items-center gap-x-2.5">
                        <!-- Create button for person -->
                        <div class="flex items-center gap-x-2.5">
                            {!! view_render_event('admin.quotes.index.create_button.before') !!}
                            
                            @if (bouncer()->hasPermission('quotes.create'))
                                <a 
                                    href="{{ route('admin.factures.create') }}"
                                    class="primary-button"
                                >
                                    Créer une facture
                                </a>
                            @endif
            
                            {!! view_render_event('admin.quotes.index.create_button.after') !!}
                        </div>
                    </div>
                </div>
            
                {!! view_render_event('admin.quotes.index.datagrid.before') !!}
            
                <!-- DataGrid -->
                <x-admin::datagrid :src="route('admin.factures.index')" />

                {!! view_render_event('admin.quotes.index.datagrid.after') !!}
            </div>
        </script>

        <script type="module">
            app.component('v-facture', {
                template: '#v-facture-template',
            });
        </script>

        <!-- Facture Relance Popup Template -->
        <script type="text/x-template" id="v-facture-relance-template">
            <Teleport to="body">
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    enctype="multipart/form-data"
                    as="div"
                >
                    <form
                        @submit="handleSubmit($event, save)"
                        ref="relanceForm"
                    >
                        <x-admin::modal
                            ref="relanceModal"
                            position="bottom-right"
                            @toggle="removeTinyMCE"
                        >
                            <x-slot:header>
                                <h3 class="text-base font-semibold dark:text-white">
                                    Relance facture
                                </h3>
                            </x-slot>

                            <x-slot:content>
                                <!-- To -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        À
                                    </x-admin::form.control-group.label>

                                    <div class="relative">
                                        <x-admin::form.control-group.controls.tags
                                            name="reply_to"
                                            class="w-[calc(100%-62px)]"
                                            input-rules="email"
                                            rules="required"
                                            ::data="formData.reply_to"
                                            label="À"
                                            placeholder="Entrez les adresses email"
                                            ::allow-duplicates="false"
                                        />

                                        <div class="absolute top-[9px] flex items-center gap-2 ltr:right-2 rtl:left-2">
                                            <span
                                                class="cursor-pointer font-medium hover:underline dark:text-white"
                                                @click="showCC = ! showCC"
                                            >
                                                Cc
                                            </span>

                                            <span
                                                class="cursor-pointer font-medium hover:underline dark:text-white"
                                                @click="showBCC = ! showBCC"
                                            >
                                                Cci
                                            </span>
                                        </div>
                                    </div>

                                    <x-admin::form.control-group.error control-name="reply_to" />
                                </x-admin::form.control-group>

                                <template v-if="showCC">
                                    <!-- Cc -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            Cc
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.controls.tags
                                            name="cc"
                                            class="w-[calc(100%-62px)]"
                                            input-rules="email"
                                            ::data="formData.cc"
                                            label="Cc"
                                            placeholder="Entrez les adresses email"
                                        />

                                        <x-admin::form.control-group.error control-name="cc" />
                                    </x-admin::form.control-group>
                                </template>

                                <template v-if="showBCC">
                                    <!-- Bcc -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label>
                                            Cci
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.controls.tags
                                            name="bcc"
                                            class="w-[calc(100%-62px)]"
                                            input-rules="email"
                                            ::data="formData.bcc"
                                            label="Cci"
                                            placeholder="Entrez les adresses email"
                                        />

                                        <x-admin::form.control-group.error control-name="bcc" />
                                    </x-admin::form.control-group>
                                </template>

                                <!-- Subject -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        Objet
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="subject"
                                        name="subject"
                                        rules="required"
                                        v-model="formData.subject"
                                        label="Objet"
                                        placeholder="Objet de l'email"
                                    />

                                    <x-admin::form.control-group.error control-name="subject" />
                                </x-admin::form.control-group>

                                <!-- Content -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="reply"
                                        id="reply"
                                        rules="required"
                                        v-model="formData.reply"
                                        :tinymce="true"
                                        label="Message"
                                        rows="8"
                                    />

                                    <x-admin::form.control-group.error control-name="reply" />
                                </x-admin::form.control-group>

                                <!-- Attachments -->
                                <x-admin::form.control-group class="!mb-0">
                                    <x-admin::attachments
                                        allow-multiple="true"
                                        hide-button="true"
                                    />
                                </x-admin::form.control-group>
                            </x-slot>

                            <x-slot:footer>
                                <div class="flex w-full items-center justify-between">
                                    <label
                                        class="icon-attachment cursor-pointer p-1 text-2xl hover:rounded-md hover:bg-gray-100 dark:hover:bg-gray-950"
                                        for="file-upload"
                                    ></label>

                                    <x-admin::button
                                        class="primary-button"
                                        title="Envoyer"
                                        ::loading="isSending"
                                        ::disabled="isSending"
                                    />
                                </div>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </Teleport>
        </script>

        <script type="module">
            app.component('v-facture-relance', {
                template: '#v-facture-relance-template',

                data() {
                    return {
                        showCC: false,
                        showBCC: false,
                        isSending: false,
                        formData: {
                            facture_id: null,
                            reply_to: [],
                            cc: [],
                            bcc: [],
                            subject: '',
                            reply: '',
                        },
                    }
                },

                mounted() {
                    // Écouter les événements personnalisés du navigateur pour ouvrir le popup
                    window.addEventListener('open-facture-relance', (event) => {
                        this.openModal(event.detail);
                    });
                },

                methods: {
                    removeTinyMCE() {
                        tinymce?.remove?.();
                    },

                    openModal(data) {
                        // Préremplir les données du formulaire
                        this.formData.facture_id = data.id;
                        this.formData.subject = data.subject || 'Relance facture n° ' + data.id;
                        this.formData.reply = data.body || '';

                        // Préremplir les emails - extraire uniquement les valeurs (strings)
                        if (data.reply_to) {
                            let emails = [];
                            
                            if (Array.isArray(data.reply_to)) {
                                // Si c'est un tableau d'objets {value, label}, extraire les valeurs
                                emails = data.reply_to.map(item => {
                                    if (typeof item === 'string') {
                                        return item.trim();
                                    } else if (item && typeof item === 'object' && item.value) {
                                        return item.value.trim();
                                    }
                                    return null;
                                }).filter(email => email !== null);
                            } else if (typeof data.reply_to === 'string') {
                                // Si c'est une chaîne séparée par des virgules
                                emails = data.reply_to.split(',').map(email => email.trim()).filter(email => email);
                            }
                            
                            this.formData.reply_to = emails;
                        } else {
                            this.formData.reply_to = [];
                        }

                        this.formData.cc = [];
                        this.formData.bcc = [];
                        this.showCC = false;
                        this.showBCC = false;

                        this.$refs.relanceModal.open();
                    },

                    save(params, { resetForm, setErrors }) {
                        this.isSending = true;

                        let formData = new FormData(this.$refs.relanceForm);
                        formData.append('facture_id', this.formData.facture_id);

                        this.$axios.post("{{ route('admin.factures.send_relance') }}", formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                this.isSending = false;

                                this.$emitter.emit('add-flash', { 
                                    type: 'success', 
                                    message: response.data.message 
                                });

                                this.$refs.relanceModal.close();
                                this.resetForm();

                                // Le datagrid se rafraîchira automatiquement via l'événement actionSuccess
                                this.$emit('actionSuccess', response.data);
                            })
                            .catch(error => {
                                this.isSending = false;

                                if (error.response && error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                } else {
                                    this.$emitter.emit('add-flash', { 
                                        type: 'error', 
                                        message: error.response?.data?.message || 'Erreur lors de l\'envoi de l\'email' 
                                    });

                                    this.$refs.relanceModal.close();
                                }
                            });
                    },

                    resetForm() {
                        this.formData = {
                            facture_id: null,
                            reply_to: [],
                            cc: [],
                            bcc: [],
                            subject: '',
                            reply: '',
                        };
                        this.showCC = false;
                        this.showBCC = false;
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
