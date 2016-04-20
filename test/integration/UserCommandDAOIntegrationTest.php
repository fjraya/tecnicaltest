<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 19/04/16
 * Time: 17:50
 * To change this template use File | Settings | File Templates.
 */

class UserCommandDAOIntegrationTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }


    /**
    * method create
    * when userNotExist
    * should create
    */
    public function test_create_userNotExist_create()
    {
        $user = new User(array('username'=>'user1', 'password'=>md5("testPassword"), 'rol'=>User::PAGE_1));
        $userCommandDAO = new UserCommandDAO();
        $userCommandDAO->create()
    }


}