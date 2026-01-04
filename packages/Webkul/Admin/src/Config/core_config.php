<?php

return [
    /**
     * General.
     */
    [
        'key'  => 'general',
        'name' => 'admin::app.configuration.index.general.title',
        'info' => 'admin::app.configuration.index.general.info',
        'sort' => 1,
    ], [
        'key'  => 'general.general',
        'name' => 'admin::app.configuration.index.general.general.title',
        'info' => 'admin::app.configuration.index.general.general.info',
        'icon' => 'icon-setting',
        'sort' => 1,
    ], [
        'key'    => 'general.general.locale_settings',
        'name'   => 'admin::app.configuration.index.general.general.locale-settings.title',
        'info'   => 'admin::app.configuration.index.general.general.locale-settings.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'locale',
                'title'   => 'admin::app.configuration.index.general.general.locale-settings.title',
                'type'    => 'select',
                'default' => 'en',
                'options' => 'Webkul\Core\Core@locales',
            ],
        ],
    ], [
        'key'    => 'general.general.admin_logo',
        'name'   => 'admin::app.configuration.index.general.general.admin-logo.title',
        'info'   => 'admin::app.configuration.index.general.general.admin-logo.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'logo_image',
                'title'         => 'admin::app.configuration.index.general.general.admin-logo.logo-image',
                'type'          => 'image',
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ],

    /**
     * Informations commerciales
     */
    [
        'key'    => 'general.general.ninea',
        'name'   => 'Ninea',
        'info'   => 'Numéro d\'identification nationale des entreprises et associations',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'ninea',
                'title'         => 'Numéro NINEA',
                'type'          => 'text',
                'default'       => '009103706',
                'validation'    => 'max:50',
            ],
        ],
    ], [
        'key'    => 'general.general.registre_commerce',
        'name'   => 'Registre de commerce',
        'info'   => 'Numéro d\'immatriculation au registre de commerce',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'registre_commerce',
                'title'         => 'Numéro de registre',
                'type'          => 'text',
                'default'       => 'SN DKR-2022-B-485',
                'validation'    => 'max:100',
            ],
        ],
    ],

    /**
     * Informations bancaires
     */
    [
        'key'    => 'general.general.swift_code',
        'name'   => 'Code SWIFT/BIC',
        'info'   => 'Code d\'identification bancaire internationale',
        'sort'   => 5,
        'fields' => [
            [
                'name'          => 'swift_code',
                'title'         => 'Code SWIFT/BIC',
                'type'          => 'text',
                'validation'    => 'max:11',
                'info'          => 'Exemple: SGALSNDA',
            ],
        ],
    ], [
        'key'    => 'general.general.iban',
        'name'   => 'IBAN',
        'info'   => 'International Bank Account Number',
        'sort'   => 6,
        'fields' => [
            [
                'name'          => 'iban',
                'title'         => 'Numéro IBAN',
                'type'          => 'text',
                'default'       => 'SN012 01312 036 203828401 55',
                'validation'    => 'max:34',
                'info'          => 'Exemple: SN012 01312 036 203828401 55',
            ],
        ],
    ], [
        'key'    => 'general.general.rib',
        'name'   => 'RIB',
        'info'   => 'Relevé d\'Identité Bancaire',
        'sort'   => 7,
        'fields' => [
            [
                'name'          => 'rib',
                'title'         => 'Numéro RIB',
                'type'          => 'text',
                'validation'    => 'max:27',
                'info'          => 'Exemple: 01234 56789 12345678901 12',
            ],
        ],
    ], [
        'key'    => 'general.general.nom_banque',
        'name'   => 'Nom de la banque',
        'info'   => 'Nom de l\'établissement bancaire',
        'sort'   => 8,
        'fields' => [
            [
                'name'          => 'nom_banque',
                'title'         => 'Nom de la banque',
                'type'          => 'text',
                'validation'    => 'max:100',
                'default'       => 'Société Générale',
                'info'          => 'Exemple: Société Générale Sénégal',
            ],
        ],
    ], [
        'key'    => 'general.general.numero_compte',
        'name'   => 'Numéro de compte bancaire',
        'info'   => 'Numéro de compte bancaire',
        'sort'   => 9,
        'fields' => [
            [
                'name'          => 'numero_compte',
                'title'         => 'Numéro de compte',
                'type'          => 'text',
                'validation'    => 'max:50',
                'info'          => 'Exemple: 20382840155',
            ],
        ],
    ], [
        'key'    => 'general.general.adresse_siege',
        'name'   => 'Adresse du siège social',
        'info'   => 'Adresse complète du siège social',
        'sort'   => 10,
        'fields' => [
            [
                'name'          => 'adresse_siege',
                'title'         => 'Adresse complète',
                'type'          => 'textarea',
                'default'       => 'HLM 5 Villa N°1744, Dakar, Sénégal',
                'validation'    => 'max:255',
                'info'          => 'Adresse complète avec code postal et ville',
            ],
        ],
    ],

    /**
     * Paramètres généraux (suite)
     */
    [
        'key'    => 'general.settings',
        'name'   => 'admin::app.configuration.index.general.settings.title',
        'info'   => 'admin::app.configuration.index.general.settings.info',
        'icon'   => 'icon-configuration',
        'sort'   => 2,
    ], [
        'key'    => 'general.settings.footer',
        'name'   => 'admin::app.configuration.index.general.settings.footer.title',
        'info'   => 'admin::app.configuration.index.general.settings.footer.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'       => 'label',
                'title'      => 'admin::app.configuration.index.general.settings.footer.powered-by',
                'type'       => 'editor',
                'default'    => '...',
                'tinymce'    => true,
            ],
        ],
    ], [
        'key'    => 'general.settings.menu',
        'name'   => 'admin::app.configuration.index.general.settings.menu.title',
        'info'   => 'admin::app.configuration.index.general.settings.menu.info',
        'sort'   => 2,
        'fields' => [
            [
                'name'       => 'dashboard',
                'title'      => 'admin::app.configuration.index.general.settings.menu.dashboard',
                'type'       => 'text',
                'default'    => 'Dashboard',
                'validation' => 'max:20',
            ], [
                'name'       => 'leads',
                'title'      => 'admin::app.configuration.index.general.settings.menu.leads',
                'type'       => 'text',
                'default'    => 'Leads',
                'validation' => 'max:20',
            ], [
                'name'       => 'quotes',
                'title'      => 'admin::app.configuration.index.general.settings.menu.quotes',
                'type'       => 'text',
                'default'    => 'Quotes',
                'validation' => 'max:20',
            ], [
                'name'       => 'mail.mail',
                'title'      => 'admin::app.configuration.index.general.settings.menu.mail',
                'type'       => 'text',
                'default'    => 'Mail',
                'validation' => 'max:20',
            ], [
                'name'       => 'mail.inbox',
                'title'      => 'admin::app.configuration.index.general.settings.menu.inbox',
                'type'       => 'text',
                'default'    => 'Inbox',
                'validation' => 'max:20',
            ], [
                'name'       => 'mail.draft',
                'title'      => 'admin::app.configuration.index.general.settings.menu.draft',
                'type'       => 'text',
                'default'    => 'Draft',
                'validation' => 'max:20',
            ], [
                'name'       => 'mail.outbox',
                'title'      => 'admin::app.configuration.index.general.settings.menu.outbox',
                'type'       => 'text',
                'default'    => 'Outbox',
                'validation' => 'max:20',
            ], [
                'name'       => 'mail.sent',
                'title'      => 'admin::app.configuration.index.general.settings.menu.sent',
                'type'       => 'text',
                'default'    => 'Sent',
                'validation' => 'max:20',
            ], [
                'name'       => 'mail.trash',
                'title'      => 'admin::app.configuration.index.general.settings.menu.trash',
                'type'       => 'text',
                'default'    => 'Trash',
                'validation' => 'max:20',
            ], [
                'name'       => 'activities',
                'title'      => 'admin::app.configuration.index.general.settings.menu.activities',
                'type'       => 'text',
                'default'    => 'Activities',
                'validation' => 'max:20',
            ], [
                'name'       => 'contacts.contacts',
                'title'      => 'admin::app.configuration.index.general.settings.menu.contacts',
                'type'       => 'text',
                'default'    => 'Contacts',
                'validation' => 'max:20',
            ], [
                'name'       => 'contacts.persons',
                'title'      => 'admin::app.configuration.index.general.settings.menu.persons',
                'type'       => 'text',
                'default'    => 'Persons',
                'validation' => 'max:20',
            ], [
                'name'       => 'contacts.organizations',
                'title'      => 'admin::app.configuration.index.general.settings.menu.organizations',
                'type'       => 'text',
                'default'    => 'Organizations',
                'validation' => 'max:20',
            ], [
                'name'       => 'products',
                'title'      => 'admin::app.configuration.index.general.settings.menu.products',
                'type'       => 'text',
                'default'    => 'Products',
                'validation' => 'max:20',
            ], [
                'name'       => 'settings',
                'title'      => 'admin::app.configuration.index.general.settings.menu.settings',
                'type'       => 'text',
                'default'    => 'Settings',
                'validation' => 'max:20',
            ], [
                'name'       => 'configuration',
                'title'      => 'admin::app.configuration.index.general.settings.menu.configuration',
                'type'       => 'text',
                'default'    => 'Configuration',
                'validation' => 'max:20',
            ],
        ],
    ], [
        'key'    => 'general.settings.menu_color',
        'name'   => 'admin::app.configuration.index.general.settings.menu-color.title',
        'info'   => 'admin::app.configuration.index.general.settings.menu-color.info',
        'sort'   => 3,
        'fields' => [
            [
                'name'    => 'brand_color',
                'title'   => 'admin::app.configuration.index.general.settings.menu-color.brand-color',
                'type'    => 'color',
                'default' => '#0E90D9',
            ],
        ],
    ], [
        'key'  => 'general.magic_ai',
        'name' => 'admin::app.configuration.index.magic-ai.title',
        'info' => 'admin::app.configuration.index.magic-ai.info',
        'icon' => 'icon-setting',
        'sort' => 3,
    ], [
        'key'    => 'general.magic_ai.settings',
        'name'   => 'admin::app.configuration.index.magic-ai.settings.title',
        'info'   => 'admin::app.configuration.index.magic-ai.settings.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enable',
                'title'         => 'admin::app.configuration.index.magic-ai.settings.enable',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'api_key',
                'title'         => 'admin::app.configuration.index.magic-ai.settings.api-key',
                'type'          => 'password',
                'depends'       => 'enable:1',
                'validation'    => 'required_if:enable,1',
                'info'          => 'admin::app.configuration.index.magic-ai.settings.api-key-info',
            ], [
                'name'          => 'model',
                'title'         => 'admin::app.configuration.index.magic-ai.settings.models.title',
                'type'          => 'select',
                'channel_based' => true,
                'depends'       => 'enable:1',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.magic-ai.settings.models.gpt-4o',
                        'value' => 'openai/chatgpt-4o-latest',
                    ], [
                        'title' => 'admin::app.configuration.index.magic-ai.settings.models.gpt-4o-mini',
                        'value' => 'openai/gpt-4o-mini',
                    ], [
                        'title' => 'admin::app.configuration.index.magic-ai.settings.models.gemini-2-0-flash-001',
                        'value' => 'google/gemini-2.0-flash-001',
                    ], [
                        'title' => 'admin::app.configuration.index.magic-ai.settings.models.deepseek-r1',
                        'value' => 'deepseek/deepseek-r1-distill-llama-8b',
                    ], [
                        'title' => 'admin::app.configuration.index.magic-ai.settings.models.llama-3-2-3b-instruct',
                        'value' => 'meta-llama/llama-3.2-3b-instruct',
                    ], [
                        'title' => 'admin::app.configuration.index.magic-ai.settings.models.grok-2-1212',
                        'value' => 'x-ai/grok-2-1212',
                    ],
                ],
            ], [
                'name'          => 'other_model',
                'title'         => 'admin::app.configuration.index.magic-ai.settings.other',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.magic-ai.settings.other-model',
                'default'       => null,
                'depends'       => 'enable:1',
            ],
        ],
    ], [
        'key'    => 'general.magic_ai.doc_generation',
        'name'   => 'admin::app.configuration.index.magic-ai.settings.doc-generation',
        'info'   => 'admin::app.configuration.index.magic-ai.settings.doc-generation-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'admin::app.configuration.index.magic-ai.settings.enable',
                'type'          => 'boolean',
            ],
        ],
    ],

    /**
     * Email.
     */
    [
        'key'  => 'email',
        'name' => 'admin::app.configuration.index.email.title',
        'info' => 'admin::app.configuration.index.email.info',
        'sort' => 2,
    ], [
        'key'  => 'email.imap',
        'name' => 'admin::app.configuration.index.email.imap.title',
        'info' => 'admin::app.configuration.index.email.imap.info',
        'icon' => 'icon-setting',
        'sort' => 1,
    ], [
        'key'    => 'email.imap.account',
        'name'   => 'admin::app.configuration.index.email.imap.account.title',
        'info'   => 'admin::app.configuration.index.email.imap.account.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'host',
                'title'   => 'admin::app.configuration.index.email.imap.account.host',
                'type'    => 'text',
                'default' => config('imap.accounts.default.host'),
            ],
            [
                'name'    => 'port',
                'title'   => 'admin::app.configuration.index.email.imap.account.port',
                'type'    => 'text',
                'default' => config('imap.accounts.default.port'),
            ],
            [
                'name'    => 'encryption',
                'title'   => 'admin::app.configuration.index.email.imap.account.encryption',
                'type'    => 'text',
                'default' => config('imap.accounts.default.encryption'),
            ],
            [
                'name'    => 'validate_cert',
                'title'   => 'admin::app.configuration.index.email.imap.account.validate-cert',
                'type'    => 'boolean',
                'default' => config('imap.accounts.default.validate_cert'),
            ],
            [
                'name'    => 'username',
                'title'   => 'admin::app.configuration.index.email.imap.account.username',
                'type'    => 'text',
                'default' => config('imap.accounts.default.username'),
            ],
            [
                'name'    => 'password',
                'title'   => 'admin::app.configuration.index.email.imap.account.password',
                'type'    => 'password',
                'default' => config('imap.accounts.default.password'),
            ],
        ],
    ],
];