<?php
return [
    'route' => [
        'index' => [
            'controller' => 'IndexController',
            'action' => 'indexAction'
        ],
        'sendmail' => [
            'controller' => 'MailerController',
            'action' => 'sendMailAction'
        ]
    ]
];