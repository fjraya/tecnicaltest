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
        $this->assertEquals("user:username,rol:3,password:5f4dcc3b5aa765d61d8327deb882cf99", $result->toString());
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
            ->with('rol', User::PAGE_3);
        $this->sut->login('username', 'password');
    }


    /**
     * dataProvider getSessionData
     * **/
    public function getSessionData()
    {
        $map = array(array('username', 'user1'), array('rol', User::PAGE_3));
        $map2 = array(array('username', 'user1'), array('rol', null));
        $map3 = array(array('username', null), array('rol', User::PAGE_3));
        $map4 = array(array('username', null), array('rol', null));

        return array(
            array($map, new ViewUser('user1', User::PAGE_3)),
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


    private function configureUserQueryDAOStub()
    {
        $user = new User('username', md5('password'), User::PAGE_3);
        $this->userQueryDAOStub->expects($this->any())->method("readByIdWithPassword")->will($this->returnValue($user));
    }


}