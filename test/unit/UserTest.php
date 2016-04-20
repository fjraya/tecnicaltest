<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 19/04/16
 * Time: 18:02
 * To change this template use File | Settings | File Templates.
 */
require_once  __DIR__."/../../src/model/User.php";
class UserTest extends PHPUnit_Framework_TestCase
{

    /**
     * dataProvider constructData
     */
    public function constructData()
    {
        return array(
            array("", "testPassword", "testRol"),
            array("testUser", "", "testRol"),
            array("testUser", "testPassword", ""),
            array(null, "testPassword", "testRol"),
            array("testUser", null, "testRol"),
            array("testUser", "testPassword", null),
            array("", "", "testRol"),
            array("testUser", "", ""),
            array("", "testPassword", ""),
        );
    }

   /**
   * method construct
   * when calledWithInvalidParams
   * should throw
   * @dataProvider constructData
    * @expectedException DomainException
   */
   public function test_construct_calledWithInvalidParams_throw($username, $password, $rol)
   {
       new User($username, $password, $rol);
   }

   /**
   * method construct
   * when calledWithOneInvalidParam
   * should correctMessageInException
    * @expectedException DomainException
    * @expectedExceptionMessage El rol no puede estar vacío
   */
   public function test_construct_calledWithOneInvalidParam_correctMessageInException()
   {
       new User("testUser", "", "");
   }

    /**
    * method construct
    * when calledWithCorrectParams
    * should correctConstruction
    */
    public function test_construct_calledWithCorrectParams_correctConstruction()
    {
        $sut = new User("testUser", "testPassword", "testRol");
        $expected = "user:testUser,rol:testRol,password:testPassword";
        $this->assertEquals($expected, $sut->toString());
    }


    /**
    * dataProvider getPasswordData
     * **/
    public function getPasswordData(){
        return array(
            array("aPassword", "aPassword", true),
            array("aPassword", "noPassword", false)
        );
    }


    /**
    * method hasSamePassword
    * when called
    * should returnCorrectAnswer
     * @dataProvider getPasswordData
    */
    public function test_hasSamePassword_called_returnCorrectAnswer($sourcePassword, $comparePassword, $expected)
    {
        $user = new User("user1", md5($sourcePassword), User::PAGE_3);
        $actual = $user->hasSamePassword($comparePassword);
        $this->assertEquals($expected, $actual);
    }


    /**
    * dataProvider getNullPasswordData
     * **/
    public function getNullPasswordData(){
        return array(
            array(null), array("")
        );
    }

    /**
    * method hasSamePassword
    * when calledWithNullPassword
    * should throw
     * @dataProvider getNullPasswordData
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Password no puede ser vacío
    */
    public function test_hasSamePassword_calledWithNullPassword_throw($password)
    {
        $user = new User('username', 'password', User::PAGE_3);
        $user->hasSamePassword($password);
    }



}