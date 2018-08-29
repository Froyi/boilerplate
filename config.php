<?php
use Project\ConfigurationInterface;

return [
    'project' => [
        'name' => 'Testproject',
        'namespace' => 'Project'
    ],
    'template' => [
        'name' => 'default',
        'dir' =>  '/default',
        'main_css_path' => '/css/main.css'
    ],
    'controller' => [
        'namespace' => 'Controller'
    ],
    'mailer' => [
        'server' => ConfigurationInterface::DEFAULT_SERVER,
        'port' => 25,
        ConfigurationInterface::USER => 'web1061p1',
        ConfigurationInterface::PASS => 'j5q9hCZp',
        'standard_from_mail' => 'test@boilerplate.ms2002.alfahosting.org',
        'standard_from_name' => 'John Doe'
    ]
];