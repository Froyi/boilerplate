<?php
return [
    'route' => [
        /** Index Controller */
        'index' => [
            'controller' => 'IndexController',
            'action' => 'indexAction'
        ],

        /** MailerController */
        'sendmail' => [
            'controller' => 'MailerController',
            'action' => 'sendMailAction'
        ],

        /** JsonController */

        /** SyncController */
        'migrate' => [
            'controller' => 'SyncController',
            'action' => 'migrateAction'
        ]
    ]
];