<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 19/04/16
 * Time: 17:50
 * To change this template use File | Settings | File Templates.
 */
require_once  __DIR__."/../../src/model/User.php";
require_once  __DIR__."/../../src/dal/UserCommandDAO.php";
class UserCommandDAOIntegrationTest extends PHPUnit_Framework_TestCase
{
    private $sut;
    private $database = "integration/resources/test.sqlite";
    private $stmt;


    protected function setUp()
    {
        $this->stmt = new SQLite3($this->database);
        $this->sut = new UserCommandDAO($this->database);
        $this->sut->truncateTable();
    }


    /**
     * method create
     * when userNotExist
     * should create
     */
    public function test_create_userNotExist_create()
    {
        $times = $this->stmt->querySingle("select count(*) from users where username='user1'");
        $this->assertEquals(0, $times, "guard - usuario 'user1' ya existe");
        $user = $this->getTestUser();
        $this->sut->create($user);
        $actual = $this->querySingle();
        $this->assertEquals(array('username' => 'user1', 'password' => '7c6a180b36896a0a8c02787eeafb0e4c', 'roles' => User::PAGE_2), $actual);
    }


    /**
     * method create
     * when userExists
     * should throw
     * @expectedException DomainException
     */
    public function test_create_userExists_throw()
    {
        $user = $this->getTestUser();
        $this->sut->create($user);
        $this->sut->create($user);
    }


    /**
     * method update
     * when userNotExists
     * should nothingHappens
     */
    public function test_update_userNotExists_nothingHappens()
    {
        $user = $this->getTestUser();
        $this->sut->update($user);
        $actual = $this->querySingle();
        $this->assertEquals(array(), $actual);
    }


    /**
     * method update
     * when userExists
     * should doUpdate
     */
    public function test_update_userExists_doUpdate()
    {
        $user = $this->getTestUser();
        $this->sut->create($user);
        $user->setRoles(array(User::PAGE_3, User::PAGE_1));
        $this->sut->update($user);
        $actual = $this->querySingle();
        $this->assertEquals(array('username' => 'user1', 'password' => '7c6a180b36896a0a8c02787eeafb0e4c', 'roles' => 'PAGE_3,PAGE_1'), $actual);
    }


    /**
     * @return User
     */
    private function getTestUser()
    {
        $user = new User("user1", "password1", array(User::PAGE_2));
        return $user;
    }


    /**
     * @param $stmt
     * @return mixed
     */
    private function querySingle()
    {
        $actual = $this->stmt->querySingle("select * from users where username='user1'", true);
        return $actual;
    }

}