<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 11:42
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/../../src/services/UserService.php";
require_once __DIR__ . "/../../src/dal/ICommandDAO.php";
require_once __DIR__ . "/../../src/model/ViewUser.php";
class UserServiceTest extends PHPUnit_Framework_TestCase
{

    private $userQueryDAODouble;
    private $userCommandDAODouble;
    private $sut;

    protected function setUp()
    {
        $this->userQueryDAODouble = $this->getMock("IUserQueryDAO");
        $this->userCommandDAODouble = $this->getMock("ICommandDAO");
        $this->sut = new UserService($this->userCommandDAODouble, $this->userQueryDAODouble);
    }

    /**
     * dataProvider getListUsersData
     * **/
    public function getListUsersData()
    {
        return array(
            array(ViewUser::ADMIN, 'u1|u2|'),
            array(ViewUser::PAGE_1, 'u4|')
        );
    }

    /**
     * method listUsersByUser
     * when called
     * should returnCorrectUsers
     * @dataProvider getListUsersData
     */
    public function test_listUsersByUser_called_returnCorrectUsers($rol, $expected)
    {
        $testUser = new ViewUser('userAdmin', array($rol));
        $this->userQueryDAODouble->method("readAll")->will($this->returnValue(array(new ViewUser('u1', array(ViewUser::PAGE_3, ViewUser::PAGE_1)), new ViewUser('u2', array(ViewUser::PAGE_2, ViewUser::PAGE_1)))));
        $this->userQueryDAODouble->method("readById")->will($this->returnValue(new ViewUser('u4', array(ViewUser::PAGE_3, ViewUser::PAGE_1))));

        $actual = $this->sut->listUsersByUser($testUser);
        $this->assertEquals($expected, $this->serialize($actual));
    }


    /**
     * method listUsersByUser
     * when calledWithAdmin
     * should correctCallToInnerReadAll
     */
    public function test_listUsersByUser_calledWithAdmin_correctCallToInnerReadAll()
    {
        $testUser = new ViewUser('userAdmin', array(ViewUser::ADMIN));
        $this->userQueryDAODouble->expects($this->once())->method("readAll");
        $this->userQueryDAODouble->expects($this->never())->method("readById");

        $this->sut->listUsersByUser($testUser);
    }


    /**
     * method listUsersByUser
     * when calledWithNoAdmin
     * should correctCallToInnerReadById
     */
    public function test_listUsersByUser_calledWithNoAdmin_correctCallToInnerReadById()
    {
        $testUser = new ViewUser('user2', array(ViewUser::PAGE_1));
        $this->userQueryDAODouble->expects($this->never())->method("readAll");
        $this->userQueryDAODouble->expects($this->once())->method("readById")->with('user2');

        $this->sut->listUsersByUser($testUser);
    }


    /**
     * method listUsersByUser
     * when innerQueryDAOThrowException
     * should throw
     * @expectedException DomainException
     * @expectedExceptionMessage an exception
     */
    public function test_listUsersByUser_innerQueryDAOThrowException_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::ADMIN));
        $this->userQueryDAODouble->method("readAll")->will($this->throwException(new DomainException("an exception")));
        $this->sut->listUsersByUser($testUser);
    }


    /**
     * method listUsersByUser
     * when innerQueryReadByIdThrowException
     * should throw
     * @expectedException DomainException
     * @expectedExceptionMessage an exception
     */
    public function test_listUsersByUser_innerQueryReadByIdThrowException_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::PAGE_1));
        $this->userQueryDAODouble->method("readById")->will($this->throwException(new DomainException("an exception")));
        $this->sut->listUsersByUser($testUser);
    }


    /**
     * method createUser
     * when calledWithExistentUserWithSameUsername
     * should throw
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage user con username u2 ya existe
     */
    public function test_createUser_calledWithExistentUserWithSameUsername_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::ADMIN));
        $this->userQueryDAODouble->method("readById")->will($this->returnValue(new ViewUser("u2", array(ViewUser::PAGE_2))));
        $this->sut->createUser($testUser, new User('u2', 'pass3', array(ViewUser::PAGE_2)));
    }


    /**
     * method createUser
     * when calledWithNoAdmin
     * should throw
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No tiene permisos de admin para crear usuarios
     */
    public function test_createUser_calledWithNoAdmin_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::PAGE_1));
        $this->sut->createUser($testUser, null);
    }


    /**
     * method createUser
     * when calledWithAdminAndNoExistUserWithSameUsername
     * should correctCallToCreation
     */
    public function test_createUser_calledWithAdminAndNoExistUserWithSameUsername_correctCallToCreation()
    {
        $testUser = new ViewUser('user2', array(ViewUser::ADMIN));
        $user = new User('user3', 'assword', array(ViewUser::PAGE_1));
        $this->userQueryDAODouble->method("readById")->will($this->throwException(new DomainException()));
        $this->userCommandDAODouble->expects($this->once())->method('create')->with($user);
        $this->sut->createUser($testUser, $user);
    }


    /**
     * method updateUser
     * when calledWithNoAdmin
     * should throw
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No tiene permisos de admin para modificar usuarios
     */
    public function test_updateUser_calledWithNoAdmin_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::PAGE_1));
        $this->sut->updateUser($testUser, "user", "pass", "PAGE_1");
    }

    /**
     * method updateUser
     * when userNotExists
     * should throw
     * @expectedException DomainException
     */
    public function test_updateUser_userNotExists_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::ADMIN));
        $this->userQueryDAODouble->method("readByIdWithPassword")->will($this->throwException(new DomainException()));
        $this->sut->updateUser($testUser, "user", "pass", "PAGE_1");
    }


    /**
     * method deleteUser
     * when userNotExists
     * should throw
     * @expectedException DomainException
     */
    public function test_deleteUser_userNotExists_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::ADMIN));
        $this->userQueryDAODouble->method("readByIdWithPassword")->will($this->throwException(new DomainException()));
        $this->sut->deleteUser($testUser, "user");
    }

    /**
     * method deleteUser
     * when calledWithNoAdmin
     * should throw
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No tiene permisos de admin para borrar usuarios
     */
    public function test_deleteUser_calledWithNoAdmin_throw()
    {
        $testUser = new ViewUser('user2', array(ViewUser::PAGE_1));
        $this->sut->deleteUser($testUser, "user");
    }

    /**
     * method deleteUser
     * when userExistsAndAdminTryToDelete
     * should correctDelete
     */
    public function test_deleteUser_userExistsAndAdminTryToDelete_correctDelete()
    {
        $testUser = new ViewUser('user2', array(ViewUser::ADMIN));
        $this->userQueryDAODouble->method("readByIdWithPassword")->will($this->returnValue(new User("u2", 'pass4', array(ViewUser::PAGE_2))));
        $this->userCommandDAODouble->expects($this->once())->method("delete")->with(new User("u2", 'pass4', array(ViewUser::PAGE_2)));
        $this->sut->deleteUser($testUser, 'u2');
    }


    /**
     * method updateUser
     * when userExistsAndAdminTryToUpdate
     * should correctUpdate
     */
    public function test_updateUser_userExistsAndAdminTryToUpdate_correctUpdate()
    {
        $testUser = new ViewUser('user2', array(ViewUser::ADMIN));
        $this->userQueryDAODouble->method("readByIdWithPassword")->will($this->returnValue(new User("u2", 'pass4', array(ViewUser::PAGE_2))));
        $this->userCommandDAODouble->expects($this->once())->method("update")->with(new User("u2", 'pass4', array(ViewUser::PAGE_1, ViewUser::PAGE_2)));
        $this->sut->updateUser($testUser, 'u2', null, ViewUser::PAGE_1 . "," . ViewUser::PAGE_2);
    }


    private function serialize($items)
    {
        $result = "";
        foreach ($items as $item) {
            $result .= $item->getUsername() . "|";
        }
        return $result;
    }


}