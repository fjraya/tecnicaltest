<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 15:49
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/ApiRestUserController.php";
require_once __DIR__ . "/UserController.php";
require_once __DIR__ . "/../helpers/JSONParser.php";
require_once __DIR__ . "/../helpers/XMLParser.php";

class ControllerLocator
{
    public function getInstance($handle, $contentNegotiation, $vars, $loginService = null, $userService = null)
    {
        switch($handle) {
            case "UserController": return new UserController($loginService);
            case "ApiRestUserController":
                switch($contentNegotiation) {
                    case "application/xml":
                        return new ApiRestUserController($vars, $loginService, $userService, new XMLParser());
                    case "application/json":
                        return new ApiRestUserController($vars, $loginService, $userService, new JSONParser());
                    default:
                        return new ApiRestUserController($vars, $loginService, $userService, new JSONParser());

                }
        }
    }
}