<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 19/04/16
 * Time: 18:02
 * To change this template use File | Settings | File Templates.
 */
require_once "../../src/model/User.php";
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
    * @expectedExceptionMessage El password no puede estar vacío
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
        $expected = "user:testUser,password:testPassword,rol:testRol";
        $this->assertEquals($expected, $sut->toString());
    }




}