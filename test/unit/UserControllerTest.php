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
    private $redirect;

    public function render($template, $params = array())
    {
        $this->render = $template . "|" . json_encode($params);
    }

    public function getActual()
    {
        return $this->render;
    }


    public function redirect($uri)
    {
        $this->redirect = "redirect|".$uri;
    }

    public function getRedirect()
    {
        return $this->redirect;
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
            array('getPage1', false, 'login|{"uri":"\/page1","errorMsg":null}'),
            array('getPage1', new ViewUser('username1', array(ViewUser::PAGE_3)), 'forbidden|{"uri":"\/page1"}'),
            array('getPage1', new ViewUser('username1', array(ViewUser::PAGE_1)), 'welcome|{"pagename":"page1","username":"username1"}'),
            array('getPage2', false, 'login|{"uri":"\/page2","errorMsg":null}'),
            array('getPage2', new ViewUser('username1', array(ViewUser::PAGE_3)), 'forbidden|{"uri":"\/page2"}'),
            array('getPage2', new ViewUser('username1', array(ViewUser::PAGE_1, ViewUser::PAGE_2)), 'welcome|{"pagename":"page2","username":"username1"}'),
            array('getPage2', new ViewUser('username1', array(ViewUser::PAGE_1)), 'forbidden|{"uri":"\/page2"}'),
            array('getPage3', false, 'login|{"uri":"\/page3","errorMsg":null}'),
            array('getPage3', new ViewUser('username1', array(ViewUser::PAGE_3)), 'welcome|{"pagename":"page3","username":"username1"}'),
            array('getPage3', new ViewUser('username1', array(ViewUser::PAGE_1)), 'forbidden|{"uri":"\/page3"}'),
            array('getPage1', new ViewUser('username1', array(ViewUser::ADMIN)), 'welcome|{"pagename":"page1","username":"username1"}'),
            array('getPage2', new ViewUser('username1', array(ViewUser::ADMIN)), 'welcome|{"pagename":"page2","username":"username1"}'),
            array('getPage3', new ViewUser('username1', array(ViewUser::ADMIN)), 'welcome|{"pagename":"page3","username":"username1"}'),
            array('getPage3', new ViewUser('username1', array(ViewUser::PAGE_1, ViewUser::ADMIN)), 'forbidden|{"uri":"\/page3"}'),
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
        $this->verifyRender($expected);
    }


    /**
    * dataProvider getLoginExceptionData
     * **/
    public function getLoginExceptionData(){
        return array(
            array("InvalidArgumentException", 'login|{"errorMsg":"username o password no pueden ser nulos","uri":"auri.com"}'),
            array("DomainException", 'login|{"errorMsg":"login incorrecto","uri":"auri.com"}')
        );
    }


    /**
     * method login
     * when calledWithException
     * should correctRender
     * @dataProvider getLoginExceptionData
     */
    public function test_login_calledWithException_correctRender($exception, $expected)
    {
        $params = array('username' => 'user', 'password' => 'pass', 'uri' => 'auri.com');
        $this->loginServiceStub->expects($this->any())->method("login")->will($this->throwException(new $exception()));
        $this->sut->postLogin($params);
        $this->verifyRender($expected);
    }


    /**
    * method login
    * when calledAndSuccess
    * should correctRedirect
    */
    public function test_login_calledAndSuccess_correctRedirect()
    {
        $params = array('username' => 'user', 'password' => 'pass', 'uri' => 'auri.com');
        $this->loginServiceStub->expects($this->any())->method("login")->will($this->returnValue(new ViewUser('user', array(ViewUser::PAGE_1))));
        $this->sut->postLogin($params);

        $this->verifyRedirect();
    }


    /**
    * method logout
    * when called
    * should correctCallToInnerLoginService
    */
    public function test_logout_called_correctCallToInnerLoginService()
    {
        $params = array('uri'=>'auri.com');
        $this->loginServiceStub->expects($this->once())->method("logout");
        $this->sut->postLogout($params);
    }


    /**
    * method logout
    * when called
    * should correctRedirect
    */
    public function test_logout_called_correctRedirect()
    {
        $params = array('uri'=>'auri.com');
        $this->sut->postLogout($params);
        $this->verifyRedirect();
    }




    /**
     * @param $expected
     */
    private function verifyRender($expected)
    {
        $actual = $this->viewSpy->getActual();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $expected
     */
    private function verifyRedirect()
    {
        $expected = 'redirect|auri.com';
        $actual = $this->viewSpy->getRedirect();
        $this->assertEquals($expected, $actual);
    }


}