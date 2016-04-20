<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/../../src/views/View.php";
require_once __DIR__ . "/../../src/controllers/UserController.php";
class ViewSpy extends View
{
    private $render;

    public function render($template, $params = array())
    {
        $this->render = $template . "|" . json_encode($params);
    }

    public function getActual()
    {
        return $this->render;
    }
}

class UserControllerTest extends PHPUnit_Framework_TestCase
{

    private $loginServiceStub;
    private $sut;
    private $viewSpy;

    protected function setUp()
    {
        $this->loginServiceStub = $this->getMock("ILoginService");
        $this->viewSpy = new ViewSpy();
        $this->sut = new UserController($this->loginServiceStub, $this->viewSpy);
    }


    /**
     * dataProvider getUserSessionData
     * **/
    public function getUserSessionData()
    {
        return array(
            array('page1', false, 'login|{"uri":"\/page1","errorMsg":null}'),
            array('page1', new ViewUser('username1', ViewUser::PAGE_3), 'forbidden|[]'),
            array('page1', new ViewUser('username1', ViewUser::PAGE_1), 'welcome|{"pagename":"page1","username":"username1"}'),
            array('page2', false, 'login|{"uri":"\/page2","errorMsg":null}'),
            array('page2', new ViewUser('username1', ViewUser::PAGE_3), 'forbidden|[]'),
            array('page2', new ViewUser('username1', ViewUser::PAGE_2), 'welcome|{"pagename":"page2","username":"username1"}'),
            array('page2', new ViewUser('username1', ViewUser::PAGE_1), 'forbidden|[]'),
            array('page3', false, 'login|{"uri":"\/page3","errorMsg":null}'),
            array('page3', new ViewUser('username1', ViewUser::PAGE_3), 'welcome|{"pagename":"page3","username":"username1"}'),
            array('page3', new ViewUser('username1', ViewUser::PAGE_1), 'forbidden|[]'),
            array('page1', new ViewUser('username1', ViewUser::ADMIN), 'welcome|{"pagename":"page1","username":"username1"}'),
            array('page2', new ViewUser('username1', ViewUser::ADMIN), 'welcome|{"pagename":"page2","username":"username1"}'),
            array('page3', new ViewUser('username1', ViewUser::ADMIN), 'welcome|{"pagename":"page3","username":"username1"}'),
        );
    }

    /**
     * method page
     * when called
     * should renderCorrectPage
     * @dataProvider getUserSessionData
     */
    public function test_page_called_renderCorrectPage($method, $sessionResult, $expected)
    {
        $this->loginServiceStub->expects($this->any())->method("existUserSession")->will($this->returnValue($sessionResult));
        $this->sut->$method();
        $actual = $this->viewSpy->getActual();
        $this->assertEquals($expected, $actual);
    }


}