<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 12:33
 * To change this template use File | Settings | File Templates.
 */


require_once  __DIR__."/../../src/model/User.php";
require_once  __DIR__."/../../src/dal/UserCommandDAO.php";
require_once  __DIR__."/../../src/dal/UserQueryDAO.php";

class UserQueryDAOIntegrationTest extends PHPUnit_Framework_TestCase
{

    private $sut;
    private $database = "integration/resources/test.sqlite";
    private $command;
    protected function setUp()
    {
        $this->sut = new UserQueryDAO($this->database);
        $this->command = new UserCommandDAO($this->database);
        $this->command->truncateTable();
    }

    /**
    * method readAll
    * when notExistingUsers
    * should throw
     * @expectedException DomainException
    */
    public function test_readAll_notExistingUsers_throw()
    {
        $this->sut->readAll();
    }

    /**
    * method readAll
    * when usersExist
    * should returnAllUsers
    */
    public function test_readAll_usersExist_returnAllUsers()
    {
        $this->generateUsersFixture();
        $result = $this->sut->readAll();
        $expected = 3;
        $this->assertEquals($expected, count($result));
    }


    /**
    * method readById
    * when calledWithNoUsers
    * should throw
     * @expectedException DomainException
    */
    public function test_readById_calledWithNoUsers_throw()
    {
        $this->sut->readById("username4");
    }

    /**
    * method readById
    * when calledWithExistingUsersAndExistUsername
    * should returnCorrectUser
    */
    public function test_readById_calledWithExistingUsersAndExistUsername_returnCorrectUser()
    {
        $this->generateUsersFixture();
        $actual = $this->sut->readById("username2");
        $this->assertEquals('user:username2,rol:2', $actual->toString());
    }

    /**
    * method readByIdWithPassword
    * when calledWithNoUsers
    * should throw
     * @expectedException DomainException
    */
    public function test_readByIdWithPassword_calledWithNoUsers_throw()
    {
        $this->sut->readByIdWithPassword("username5");
    }



    /**
    * method readByIdWithPassword
    * when calledWithExistingUserName
    * should returnCorrectUser
    */
    public function test_readByIdWithPassword_calledWithExistingUserName_returnCorrectUser()
    {
        $this->generateUsersFixture();
        $actual = $this->sut->readByIdWithPassword('username2');
        $this->assertEquals('user:username2,rol:2,password:6cb75f652a9b52798eb6cf2201057c73', $actual->toString());
    }

    private function generateUsersFixture()
    {
        $user1 = new User("username1", "password1", User::PAGE_3);
        $user2 = new User("username2", "password2", User::PAGE_2);
        $user3 = new User("username3", "password3", User::PAGE_1);
        $this->command->create($user1);
        $this->command->create($user2);
        $this->command->create($user3);
    }


}
