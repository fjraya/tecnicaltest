<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 13:13
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/../../src/model/ViewUser.php";
class ViewUserTest extends PHPUnit_Framework_TestCase
{

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
     * @expectedExceptionMessage El roles no puede estar vacío
     */
    public function test_construct_calledWithOneInvalidParam_correctMessageInException()
    {
        new ViewUser("testUser", "");
    }


    /**
      * method construct
      * when calledWithInvalidFormatInRoles
      * should throw
       * @expectedException InvalidArgumentException
       * @expectedExceptionMessage Roles debe ser un array
      */
      public function test_construct_calledWithInvalidFormatInRoles_throw()
      {
          new ViewUser("username", 1);
      }


    /**
     * method construct
     * when calledWithCorrectParams
     * should correctConstruction
     */
    public function test_construct_calledWithCorrectParams_correctConstruction()
    {
        $sut = new ViewUser("testUser", array(ViewUser::PAGE_1, ViewUser::PAGE_3));
        $expected = "user:testUser,roles:PAGE_1,PAGE_3";
        $this->assertEquals($expected, $sut->toString());
    }


    /**
     * dataProvider getRolData
     * **/
    public function getRolData()
    {
        return array(
            array("hasPage1Rol", new ViewUser('user1', array(ViewUser::PAGE_1)), true),
            array("hasPage1Rol", new ViewUser('user1', array(ViewUser::PAGE_2)), false),
            array("hasPage1Rol", new ViewUser('user1', array(ViewUser::PAGE_3)), false),
            array("hasPage1Rol", new ViewUser('user1', array(ViewUser::ADMIN)), false),
            array("hasPage2Rol", new ViewUser('user1', array(ViewUser::PAGE_1)), false),
            array("hasPage2Rol", new ViewUser('user1', array(ViewUser::PAGE_2)), true),
            array("hasPage2Rol", new ViewUser('user1', array(ViewUser::PAGE_3)), false),
            array("hasPage2Rol", new ViewUser('user1', array(ViewUser::ADMIN)), false),
            array("hasPage3Rol", new ViewUser('user1', array(ViewUser::PAGE_1)), false),
            array("hasPage3Rol", new ViewUser('user1', array(ViewUser::PAGE_2)), false),
            array("hasPage3Rol", new ViewUser('user1', array(ViewUser::PAGE_3)), true),
            array("hasPage3Rol", new ViewUser('user1', array(ViewUser::ADMIN)), false),
            array("isAdmin", new ViewUser('user1', array(ViewUser::ADMIN)), true),
            array("isAdmin", new ViewUser('user1', array(ViewUser::PAGE_1)), false),
            array("isAdmin", new ViewUser('user1', array(ViewUser::PAGE_2)), false),
            array("isAdmin", new ViewUser('user1', array(ViewUser::PAGE_3)), false),
        );
    }

    /**
    * method roleMethods
    * when called
    * should returnCorrectResult
     * @dataProvider getRolData
    */
    public function test_roleMethods_called_returnCorrectResult($method, $user, $expected)
    {
        $actual = $user->$method();
        $this->assertEquals($expected, $actual);
    }



}