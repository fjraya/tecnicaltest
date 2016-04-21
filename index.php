<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 19/04/16
 * Time: 17:00
 * To change this template use File | Settings | File Templates.
 */
session_start();
require 'vendor/autoload.php';
require_once __DIR__ . "/src/controllers/ControllerLocator.php";
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/page1', 'UserController');
    $r->addRoute('GET', '/page2', 'UserController');
    $r->addRoute('GET', '/page3', 'UserController');
    $r->addRoute('POST', '/login', 'UserController');
    $r->addRoute('POST', '/logout', 'UserController');
    $r->addRoute('POST', '/users', 'ApiRestUserController');
    $r->addRoute('PUT', '/users/{username}/{roles}/{password}', 'ApiRestUserController');
    $r->addRoute('DELETE', '/users/{username}', 'ApiRestUserController');
    $r->addRoute('GET', '/users', 'ApiRestUserController');
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$contentNegotiation = $_SERVER['HTTP_ACCEPT'];
$parser = $contentNegotiation == "application/xml" ? new XMLParser() : new JSONParser();
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.0 404 Method Not Found");
        header("Content-Type: application/" . $parser->getName());
        echo $parser->parse(array('status' => "404 Method Not Found"));
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = implode(",", $routeInfo[1]);
        header("HTTP/1.0 405 Method Not Allowed");
        header("Allow: " . $allowedMethods);
        header("Content-Type: application/" . $parser->getName());
        echo $parser->parse(array('status' => "405 Method Not Allowed", 'allow' => $allowedMethods));
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
            $auth = explode(" ", $auth);

            if ($auth[0] == "Basic") {
                $credentials = explode(":", base64_decode($auth[1]));
                $vars['auth_username'] = $credentials[0];
                $vars['auth_password'] = $credentials[1];
            }
        }

        $uri = preg_replace("[^/]", "", $uri);
        $elemsUri = explode("/", $uri);
        $method = strtolower($httpMethod) . ucfirst($elemsUri[0]);
        $vars = array_merge($vars, $_POST);
        $controller = ControllerLocator::getInstance($handler, $contentNegotiation, $vars);
        $controller->$method();
        break;
}