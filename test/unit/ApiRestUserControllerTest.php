<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 16:56
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/../../src/controllers/ApiRestUserController.php";


class TssApiRestUserController extends ApiRestUserController
{
    private $spy;

    protected function response($header, $message)
    {
        $this->spy = $header . "|" . json_encode($message);
    }

    public function getActual()
    {
        return $this->spy;
    }
}

class ApiRestUserControllerTest extends PHPUnit_Framework_TestCase
{
    private $userServiceDouble;
    private $parserDouble;
    private $loginServiceDouble;
    private $sut;
    private $user;

    /**
     * @param $expected
     */
    private function exerciseGetUsersAndVerify($expected)
    {
        $this->sut->getUsers();
        $actual = $this->sut->getActual();
        $this->assertEquals($expected, $actual);
    }

    protected function setUp()
    {
        $this->vars = array('auth_username' => 'user', 'auth_password' => 'pass');
        $this->userServiceDouble = $this->getMock("IUserService");
        $this->parserDouble = $this->getMock("IParser");
        $this->loginServiceDouble = $this->getMock("ILoginService");
        $this->user = new ViewUser('user', array(ViewUser::ADMIN));
        $this->loginServiceDouble->method("login")->will($this->returnValue($this->user));
        $this->sut = new TssApiRestUserController($this->vars, $this->loginServiceDouble, $this->userServiceDouble, $this->parserDouble);
    }


    /**
     * method getUsers
     * when called
     * should correctCallToInnerUserService
     */
    public function test_getUsers_called_correctCallToInnerUserService()
    {
        $this->userServiceDouble->expects($this->once())->method("listUsersByUser")->with($this->user);
        $this->sut->getUsers();
    }


    /**
     * method getUsers
     * when called
     * should correctResponse
     */
    public function test_getUsers_called_correctResponse()
    {
        $this->userServiceDouble->expects($this->once())->method("listUsersByUser")->will($this->returnValue(array(new ViewUser('u1', array(ViewUser::PAGE_1)), new ViewUser('u2', array(ViewUser::PAGE_2)))));
        $expected = 'HTTP/1.0 200 OK|{"status":"200 OK","data":{"user1":{"username":"u1","roles":"PAGE_1"},"user2":{"username":"u2","roles":"PAGE_2"}}}';
        $this->exerciseGetUsersAndVerify($expected);
    }

    /**
     * method getUsers
     * when calledWithError
     * should correctResponse
     */
    public function test_getUsers_calledWithError_correctResponse()
    {
        $this->userServiceDouble->expects($this->once())->method("listUsersByUser")->will($this->throwException(new InvalidArgumentException("No users")));
        $expected = 'HTTP/1.0 409 Conflict|{"status":"409 Conflict","message":"No users"}';
        $this->exerciseGetUsersAndVerify($expected);
    }


    /**
     * method postUsers
     * when called
     * should correctCallToUserService
     */
    public function test_postUsers_called_correctCallToUserService()
    {
        $this->configureVars();
        $this->userServiceDouble->expects($this->once())->method("createUser")->with($this->user, new User('user1', 'pass1', explode(",", 'PAGE_1,PAGE_3')));
        $this->sut->postUsers();
    }


    /**
     * method postUsers
     * when called
     * should correctResponse
     */
    public function test_postUsers_called_correctResponse()
    {
        $this->configureVars();
        $this->userServiceDouble->method("createUser");
        $expected = 'HTTP/1.0 201 Created|{"status":"201 Created","message":"usuario creado correctamente"}';
        $this->exercisePostUsersAndVerify($expected);
    }


    /**
     * method postUsers
     * when calledWithError
     * should correctResponse
     */
    public function test_postUsers_calledWithError_correctResponse()
    {
        $this->configureVars();
        $this->userServiceDouble->method("createUser")->will($this->throwException(new DomainException("a exception")));
        $expected = 'HTTP/1.0 403 Forbidden|{"status":"403 Forbidden","message":"a exception"}';
        $this->exercisePostUsersAndVerify($expected);
    }


    /**
     * method putUsers
     * when called
     * should correctCallToUserService
     */
    public function test_putUsers_called_correctCallToUserService()
    {
        $this->configureVars();
        $this->userServiceDouble->expects($this->once())->method("updateUser")->with($this->user, 'user1', 'pass1', 'PAGE_1,PAGE_3');
        $this->sut->putUsers();
    }


    /**
     * method putUsers
     * when called
     * should correctResponse
     */
    public function test_putUsers_called_correctResponse()
    {
        $this->configureVars();
        $this->userServiceDouble->method("updateUser");
        $expected = 'HTTP/1.0 201 Created|{"status":"201 Created","message":"usuario modificado correctamente"}';
        $this->exercisePutUsersAndVerify($expected);
    }


    /**
     * method postUsers
     * when calledWithError
     * should correctResponse
     */
    public function test_putUsers_calledWithError_correctResponse()
    {
        $this->configureVars();
        $this->userServiceDouble->method("updateUser")->will($this->throwException(new DomainException("a exception")));
        $expected = 'HTTP/1.0 403 Forbidden|{"status":"403 Forbidden","message":"a exception"}';
        $this->exercisePutUsersAndVerify($expected);
    }


    private function configureVars()
    {
        $this->vars['username'] = 'user1';
        $this->vars['password'] = 'pass1';
        $this->vars['roles'] = 'PAGE_1,PAGE_3';
        $this->sut->setVars($this->vars);
    }

    /**
     * @param $expected
     */
    private function exercisePostUsersAndVerify($expected)
    {
        $this->sut->postUsers();
        $actual = $this->sut->getActual();
        $this->assertEquals($expected, $actual);
    }


    /**
     * @param $expected
     */
    private function exercisePutUsersAndVerify($expected)
    {
        $this->sut->putUsers();
        $actual = $this->sut->getActual();
        $this->assertEquals($expected, $actual);
    }

}