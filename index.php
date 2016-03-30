<?php

$environment = (!empty(getallheaders()['ENVIRONMENT'])) ? getallheaders()['ENVIRONMENT'] : 'production';
switch ($environment) {
    case 'dev':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;

    default:
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;
}


include('vendor/autoload.php');
include('Library/Config/Config.php');
\Cve\Helpers\Config::setConfig($config);

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a Logs and set then minimum log level
$logger = new Logger('CVEModel api');
$logLevel = (!empty(getallheaders()['X-LogLevel'])) ? getallheaders()['X-LogLevel'] : 'warn';
switch (strtolower($logLevel)) {
    case 'info' :
        $logLevel = Logger::INFO;
        break;
    case 'debug' :
        $logLevel = Logger::DEBUG;
        break;
    case 'error':
        $logLevel = Logger::ERROR;
        break;
    case 'warn':
    default:
        $logLevel = Logger::WARNING;
}
$logger->pushHandler(new StreamHandler('logs/' . date('Y-M-d') . '.log', $logLevel));

$urlParts = !empty($_GET['urlParts']) ? explode('/', $_GET['urlParts']) : null;
$controller = !empty($urlParts[0]) ? ucfirst($urlParts[0]) : 'Homepage';

$controllerClass = '\Cve\Controllers\\' . $controller;
if (!class_exists($controllerClass)) {
    show404();
}
$controller = new $controllerClass($logger);
unset($urlParts[0]);
if (!empty($urlParts)) {
    $urlParts = array_values($urlParts);
}
$controller->index($urlParts);

function show404()
{
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    header("HTTP/1.0 404 Not Found");
    exit;
}