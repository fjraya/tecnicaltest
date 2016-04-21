<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/../../src/helpers/XMLParser.php";
class XMLParserTest extends PHPUnit_Framework_TestCase{

    protected function setUp()
    {
    }


    /**
    * dataProvider getParseData
     * */
    public function getParseData()
    {
        return array(
            array(array(), '<?xml version="1.0" encoding="UTF-8"?><root/>'),
            array(null, '<?xml version="1.0" encoding="UTF-8"?><root/>'),
            array(array('status'=>'OK', 'data'=>array('user1'=>array('username'=>'u1', 'roles'=>'PAGE_1,PAGE_3'),'user2'=>array('username'=>'u2', 'roles'=>'PAGE_2,PAGE_1'))), '<?xml version="1.0" encoding="UTF-8"?><root><status>OK</status><data><user1><username>u1</username><roles>PAGE_1,PAGE_3</roles></user1><user2><username>u2</username><roles>PAGE_2,PAGE_1</roles></user2></data></root>'),
            array(array('status'=>'KO', 'message'=>'a message'), '<?xml version="1.0" encoding="UTF-8"?><root><status>KO</status><message>a message</message></root>'),
        );
    }

    /**
    * method parse
    * when called
    * should returnCorrectXML
     * @dataProvider getParseData
    */
    public function test_parse_called_returnCorrectXML($input, $expected)
    {
        $sut = new XMLParser();
        $actual = $sut->parse($input);
        $this->assertEquals($expected, $actual);
    }

}

