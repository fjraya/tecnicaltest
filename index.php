<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 19/04/16
 * Time: 17:00
 * To change this template use File | Settings | File Templates.
 */
session_start();
ini_set('session.cookie_secure', '0');
ini_set('session.use_cookies', '1');
require 'vendor/autoload.php';
require_once __DIR__ . "/src/controllers/UserController.php";
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/page1', 'UserController');
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/page2', 'UserController');
    // The /{title} suffix is optional
    $r->addRoute('GET', '/page3', 'UserController');
    $r->addRoute('POST', '/login', 'UserController');
    $r->addRoute('POST', '/logout', 'UserController');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.0 404 Method Not Found");
        echo "404 Method Not Found\n";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header("HTTP/1.0 405 Method Not Allowed");
        echo "405 Method Not Allowed\n";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $controller = new $handler();
        $uri = str_replace("/", "", $uri);
        $controller->$uri();
        break;
}