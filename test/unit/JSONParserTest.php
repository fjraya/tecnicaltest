<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/../../src/helpers/JSONParser.php";
class JSONParserTest extends PHPUnit_Framework_TestCase{

    protected function setUp()
    {
    }


    /**
    * dataProvider getParseData
     * */
    public function getParseData(){
        return array(
            array(array(), "[]"),
            array(null, "[]"),
            array(array('status'=>'OK', 'data'=>array('user1'=>array('username'=>'u1', 'roles'=>'PAGE_1,PAGE_3'),'user2'=>array('username'=>'u2', 'roles'=>'PAGE_2,PAGE_1'))), '{"status":"OK","data":{"user1":{"username":"u1","roles":"PAGE_1,PAGE_3"},"user2":{"username":"u2","roles":"PAGE_2,PAGE_1"}}}'),
            array(array('status'=>'KO', 'message'=>'a message'), '{"status":"KO","message":"a message"}'),
        );
    }

    /**
    * method parse
    * when called
    * should returnCorrectJSON
     * @dataProvider getParseData
    */
    public function test_parse_called_returnCorrectJSON($input, $expected)
    {
        $sut = new JSONParser();
        $actual = $sut->parse($input);
        $this->assertEquals($expected, $actual);
    }

}

