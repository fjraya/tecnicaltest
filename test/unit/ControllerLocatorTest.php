<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 18:28
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/../../src/controllers/ControllerLocator.php";
class ControllerLocatorTest extends PHPUnit_Framework_TestCase{

    protected function setUp(){
    }

    /**
    * dataProvider getLocatorData
     * **/
    public function getLocatorData(){
        return array(
            array("ApiRestUserController", "application/json", "apiRest|json"),
            array("ApiRestUserController", "application/xml", "apiRest|xml"),
            array("UserController", "application/json", "userController"),
            array("UserController", "application/xml", "userController"),
        );
    }

    /**
    * method getInstance
    * when called
    * should returnCorrectInstance
     * @dataProvider getLocatorData
    */
    public function test_getInstance_called_returnCorrectInstance($handler, $contentNegotiation, $expected)
    {
        $loginServiceStub = $this->getMock("ILoginService");
        $userServiceStub = $this->getMock("IUserService");
        $vars = array();
        $vars['auth_username'] = 'user';
        $vars['auth_password'] = 'password';
        $instance = ControllerLocator::getInstance($handler, $contentNegotiation, $vars, $loginServiceStub, $userServiceStub);
        $actual = $instance->getName();
        $this->assertEquals($expected,$actual);
    }



}