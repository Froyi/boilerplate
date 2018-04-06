<?php

namespace Project;

use Project\Controller\IndexController;
use Project\Utilities\Tools;

define('ROOT_PATH', getcwd());

require ROOT_PATH . '/vendor/autoload.php';

$route = 'index';

if (Tools::getValue('route') !== false) {
    $route = Tools::getValue('route');
}

$routing = new Routing(new Configuration());

try {
    $routing->startRoute($route);
} catch (\InvalidArgumentException $error) {
    $indexController = new IndexController();
    $indexController->errorPageAction();
}
