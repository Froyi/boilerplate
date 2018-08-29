<?php

use Project\ConfigurationInterface;

return [
    'database_live' => [
        'host' => ConfigurationInterface::DEFAULT_SERVER,
        ConfigurationInterface::USER => '',
        ConfigurationInterface::PASS => '',
        'database_name' => ''
    ],
    'database' => [
        'host' => ConfigurationInterface::DEFAULT_SERVER,
        ConfigurationInterface::USER => 'root',
        ConfigurationInterface::PASS => '',
        'database_name' => 'boilerplate'
    ],
];