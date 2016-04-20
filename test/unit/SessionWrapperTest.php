<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 15:10
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/../../src/helpers/SessionWrapper.php";
class TssSessionWrapper extends SessionWrapper
{
    private $expired;

    public function __construct($expired = null)
    {
        if (!$expired) $this->expired = false;
        else $this->expired = $expired;

    }

    protected function init()
    {

    }

    protected function isExpired($last)
    {
        return $this->expired;
    }

}

class SessionWrapperTest extends PHPUnit_Framework_TestCase
{
    private $sut;

    protected function setUp()
    {
        $_SESSION = array();
        $this->sut = new TssSessionWrapper();
    }

    /**
     * method write
     * when called
     * should generateLAST_ACTIVEkey
     */
    public function test_write_called_generateLAST_ACTIVEkey()
    {
        $this->sut->write("aKey", "aValue");
        $actual = $this->sut->read("LAST_ACTIVE");
        $this->assertTrue($actual > 0);
    }

    /**
     * method write
     * when called
     * should correctWrite
     */
    public function test_write_called_correctWrite()
    {
        $actual = $this->sut->read("aKey");
        $this->assertFalse($actual, "guard - Session contiene un key preexistente");
        $this->sut->write("aKey", "aValue");
        $actual = $this->sut->read("aKey");
        $expected = "aValue";
        $this->assertEquals($expected, $actual);
    }

    /**
    * method write
    * when badKey
    * should throw
     * @expectedException InvalidArgumentException
    */
    public function test_write_badKey_throw()
    {
        $this->sut->write(7, "aValue");
    }


    /**
    * method write
    * when calledTwiceForSameKeyAndDifferentValue
    * should returnLastValue
    */
    public function test_write_calledTwiceForSameKeyAndDifferentValue_returnLastValue()
    {
        $this->sut->write("aKey", "aValue");
        $this->sut->write("aKey", "aValue2");
        $actual = $this->sut->read("aKey");
        $expected = "aValue2";
        $this->assertEquals($expected, $actual);
    }


    /**
    * method write
    * when calledTwice
    * should correctInsertionMultipleKeys
    */
    public function test_write_calledTwice_correctInsertionMultipleKeys()
    {
        $this->sut->write("aKey", "aValue");
        $this->sut->write("aKey2", "aValue2");
        $actual = $this->sut->dump();
        $this->assertContains("aKey", $actual);
        $this->assertContains("aKey2", $actual);
    }


    /**
    * method delete
    * when calledWithInexistentKey
    * should nothingHappens
    */
    public function test_delete_calledWithInexistentKey_nothingHappens()
    {
        $this->sut->delete("aKey");
    }


    /**
    * dataProvider getDeleteData
     **/
    public function getDeleteData(){
        return array(
            array("aKey", "aKey", false),
            array("aKey", "aKey2", "aValue2"),
            array("aKey2", "aKey", "aValue"),
            array("aKey2", "aKey2", false)
        );
    }


    /**
    * method delete
    * when calledWithExistentKey
    * should correctDeletion
     * @dataProvider getDeleteData
    */
    public function test_delete_calledWithExistentKey_correctDeletion($keyToDelete, $keyToRead, $expected)
    {
        $this->sut->write("aKey", "aValue");
        $this->sut->write("aKey2", "aValue2");
        $this->sut->delete($keyToDelete);
        $actual = $this->sut->read($keyToRead);
        $this->assertEquals($expected, $actual);
    }




    /**
    * method read
    * when calledWithInexistentKey
    * should returnFalse
    */
    public function test_read_calledWithInexistentKey_returnFalse()
    {
        $actual = $this->sut->read("aKey");
        $expected = false;
        $this->assertEquals($expected, $actual);
    }


    /**
    * method read
    * when calledWithExistentKey
    * should returnCorrectValue
    */
    public function test_read_calledWithExistentKey_returnCorrectValue()
    {
        $this->sut->write("aKey", "aValue");
        $actual = $this->sut->read("aKey");
        $expected = "aValue";
        $this->assertEquals($expected, $actual);
    }



    /**
    * method destroy
    * when calledWithInexistentKeys
    * should nothingHappens
    */
    public function test_destroy_calledWithInexistentKeys_nothingHappens()
    {
        $this->sut->destroy();
    }


    /**
    * method destroy
    * when calledWithExistentKeys
    * should correctDestruction
    */
    public function test_destroy_calledWithExistentKeys_correctDestruction()
    {
        $this->sut->write("aKey", "aValue");
        $this->sut->write("aKey2", "aValue2");
        $actual = $this->sut->dump();
        $this->assertTrue(strlen($actual) > 10);
        $this->sut->destroy();
        $actual = $this->sut->dump();
        $this->assertEquals('[]', $actual);
    }


    /**
    * method write
    * when calledWithSessionExpired
    * should emptySession
    */
    public function test_write_calledWithSessionExpired_emptySession()
    {
        $this->sut = new TssSessionWrapper(true);
        $this->sut->write("aKey", "aValue");
        $actual = $this->sut->read("aKey");
        $expected = false;
        $this->assertEquals($expected, $actual);
    }
}
