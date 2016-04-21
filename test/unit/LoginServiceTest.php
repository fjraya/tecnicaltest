<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 16:00
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/../../src/services/LoginService.php";
require_once __DIR__ . "/../../src/model/User.php";
class LoginServiceTest extends PHPUnit_Framework_TestCase
{

    protected $userQueryDAOStub;
    protected $sessionWrapper;
    protected $sut;

    protected function setUp()
    {
        $this->userQueryDAOStub = $this->getMock("IUserQueryDAO");
        $this->sessionWrapper = $this->getMock("ISessionWrapper");
        $this->sut = new LoginService($this->userQueryDAOStub, $this->sessionWrapper);
    }

    /**
     * method login
     * when userNotExists
     * should throw
     * @expectedException DomainException
     */
    public function test_login_userNotExists_throw()
    {
        $this->userQueryDAOStub->expects($this->any())->method("readByIdWithPassword")->will($this->throwException(new DomainException("no existe usuario")));
        $this->sut->login("aUserName", "aPassword");
    }

    /**
     * method login
     * when userExistsButPasswordNoMach
     * should throw
     * @expectedException DomainException
     * @expectedExceptionMessage Password inválido
     */
    public function test_login_userExistsButPasswordNoMach_throw()
    {
        $this->configureUserQueryDAOStub();
        $this->sut->login('username', 'anotherPassword');
    }


    /**
     * method login
     * when userExistsButPasswordMatch
     * should returnCorrectUserResult
     */
    public function test_login_userExistsButPasswordMatch_returnCorrectUserResult()
    {
        $this->configureUserQueryDAOStub();
        $result = $this->sut->login('username', 'password');
        $this->assertEquals("user:username,roles:3,password:5f4dcc3b5aa765d61d8327deb882cf99", $result->toString());
    }


    /**
     * method login
     * when userExistsAndPasswordMatch
     * should correctCallToInnerSessionWrapper
     */
    public function test_login_userExistsAndPasswordMatch_correctCallToInnerSessionWrapper()
    {
        $this->configureUserQueryDAOStub();
        $this->sessionWrapper->expects($this->at(0))
            ->method('write')
            ->with('username', 'username');
        $this->sessionWrapper->expects($this->at(1))
            ->method('write')
            ->with('roles', array(User::PAGE_3));
        $this->sut->login('username', 'password');
    }


    /**
    * dataProvider getInvalidParamsData
     * **/
    public function getInvalidParamsData(){
        return array(
            array(null, "pass"),
            array("", "pass"),
            array("", ""),
            array(null, ""),
            array(null, null),
            array("user", ""),
            array("user", null),
        );
    }

    /**
    * method login
    * when calledWithInvalidParams
    * should throw
     * @dataProvider getInvalidParamsData
     * @expectedException InvalidArgumentException
    */
    public function test_login_calledWithInvalidParams_throw($username, $password)
    {
        $this->sut->login($username, $password);
    }



    /**
     * dataProvider getSessionData
     * **/
    public function getSessionData()
    {
        $map = array(array('username', 'user1'), array('roles', array(User::PAGE_3)));
        $map2 = array(array('username', 'user1'), array('roles', null));
        $map3 = array(array('username', null), array('roles', array(User::PAGE_3)));
        $map4 = array(array('username', null), array('roles', null));

        return array(
            array($map, new ViewUser('user1', array(User::PAGE_3))),
            array($map2, false),
            array($map3, false),
            array($map4, false)
        );
    }


    /**
     * method existUserSession
     * when called
     * should returnCorrectAnswer
     * @dataProvider getSessionData
     */
    public function test_existUserSession_called_returnCorrectAnswer($map, $expected)
    {
        $this->sessionWrapper->expects($this->any())->method("read")->will($this->returnValueMap($map));
        $actual = $this->sut->existUserSession();
        $this->assertEquals($expected, $actual);
    }



    /**
    * method logout
    * when called
    * should correctCallToInnerSessionWrapper
    */
    public function test_logout_called_correctCallToInnerSessionWrapper()
    {
        $this->sessionWrapper->expects($this->once())->method("destroy");
        $this->sut->logout();
    }




    private function configureUserQueryDAOStub()
    {
        $user = new User('username', md5('password'), array(User::PAGE_3));
        $this->userQueryDAOStub->expects($this->any())->method("readByIdWithPassword")->will($this->returnValue($user));
    }


}