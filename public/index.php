<?php


//phpinfo();  exit;

use Phalcon\Di\FactoryDefault;
use Phalcon\Autoload\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('LIB_PATH', BASE_PATH . '/lib');

$loader = new Loader();

$loader->setDirectories(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        LIB_PATH . '/WebAnalytics/',
        LIB_PATH . '/WebAnalytics/KPI/',
    ]
);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}