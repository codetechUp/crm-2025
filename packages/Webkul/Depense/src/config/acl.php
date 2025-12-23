<?php

return [
   

    [
        'key'       => 'depenses',
        'name'      => 'admin::app.depenses.index.title',
        'route'     => 'admin.depenses.index',
        'sort'      => 1,
        'icon-class'=> '',
        'children'  => [
            [
                'key'   => 'depenses.view',
                'name'  => 'admin::app.acl.view',
                'route' => 'admin.depenses.index',
                'sort'  => 1,
            ],
            [
                'key'   => 'depenses.create',
                'name'  => 'admin::app.acl.create',
                'route' => 'admin.depenses.create',
                'sort'  => 2,
            ],
            [
                'key'   => 'depenses.edit',
                'name'  => 'admin::app.acl.edit',
                'route' => 'admin.depenses.edit',
                'sort'  => 3,
            ],
            [
                'key'   => 'depenses.delete',
                'name'  => 'admin::app.acl.delete',
                'route' => 'admin.depenses.delete',
                'sort'  => 4,
            ],
        ],
    ],

];
