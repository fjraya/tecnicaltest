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
require_once __DIR__ . "/src/controllers/ControllerLocator.php";
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/page1', 'UserController');
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/page2', 'UserController');
    // The /{title} suffix is optional
    $r->addRoute('GET', '/page3', 'UserController');
    $r->addRoute('POST', '/login', 'UserController');
    $r->addRoute('POST', '/logout', 'UserController');
    $r->addRoute('POST', '/users', 'ApiRestUserController');
    $r->addRoute('PUT', '/users/{username:\w+}', 'ApiRestUserController');
    $r->addRoute('GET', '/users', 'ApiRestUserController');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$auth = $_SERVER['HTTP_AUTHORIZATION'];
$auth = explode(" ", $auth);
$contentNegotiation = $_SERVER['HTTP_ACCEPT'];
$uri = rawurldecode($uri);
echo var_export($_SERVER, true);
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
        if ($auth[0] == "Basic") {
            $credentials = explode(":",base64_decode($auth[1]));
            $vars['auth_username'] = $credentials[0];
            $vars['auth_password'] = $credentials[1];
        }

        $uri = preg_replace("[^/]", "", $uri);
        $elems = explode("/", $uri);
        //if (count($elems) < 2) $elems[0] = $uri;
        $method = strtolower($httpMethod).ucfirst($elems[0]);
        echo var_export($method, true);
        $vars = array_merge($vars, $_POST);
        echo var_export($vars, true);
        $controller = ControllerLocator::getInstance($handler, $contentNegotiation, $vars);
        $controller->$method();
        break;
}