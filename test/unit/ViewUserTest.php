<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 13:13
 * To change this template use File | Settings | File Templates.
 */
require_once  __DIR__."/../../src/model/ViewUser.php";
class ViewUserTest extends PHPUnit_Framework_TestCase{

    /**
     * dataProvider constructData
     */
    public function constructData()
    {
        return array(
            array("", "testRol"),
            array("testUser", ""),
            array(null, "testRol"),
            array("testUser", null),
        );
    }

   /**
   * method construct
   * when calledWithInvalidParams
   * should throw
   * @dataProvider constructData
    * @expectedException DomainException
   */
   public function test_construct_calledWithInvalidParams_throw($username, $rol)
   {
       new ViewUser($username, $rol);
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
       new ViewUser("testUser", "");
   }

    /**
    * method construct
    * when calledWithCorrectParams
    * should correctConstruction
    */
    public function test_construct_calledWithCorrectParams_correctConstruction()
    {
        $sut = new ViewUser("testUser", "testRol");
        $expected = "user:testUser,rol:testRol";
        $this->assertEquals($expected, $sut->toString());
    }
}