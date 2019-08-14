<?php

namespace Project;

use InvalidArgumentException;
use Project\Controller\IndexController;
use Project\Utilities\Tools;

ini_set('memory_limit', '-1');

define('ROOT_PATH', getcwd());
date_default_timezone_set('Europe/Berlin');

define('NUMERAL_SIGN', 'minus');
define('NUMERAL_HUNDREDS_SUFFIX', 'hundert');
define('NUMERAL_INFIX', 'und');

ini_set('session.gc_maxlifetime', 36000);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(36000);

session_start();

require ROOT_PATH . '/vendor/autoload.php';

$route = 'index';

if (Tools::getValue('route') !== false) {
    $route = Tools::getValue('route');
}

$configuration = Configuration::getInstance();

if ($configuration->getEntryByName('environment') !== false) {
    define('ENVIRONMENT', $configuration->getEntryByName('environment'));
} else {
    echo 'Environment is not set in environment.php';
    exit;
}
try {
    $routing = new Routing($configuration);
    $routing->startRoute($route);
} catch (InvalidArgumentException $error) {
    $indexController = new IndexController($configuration, $route);
    $indexController->errorAction();
}