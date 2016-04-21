<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 17:10
 * To change this template use File | Settings | File Templates.
 */


require_once __DIR__ . "/../services/LoginService.php";
require_once __DIR__ . "/../views/View.php";
class UserController
{
    private $loginService;
    private $view;

    public function __construct(ILoginService $loginService = null, View $view = null)
    {

        if (!$loginService) $this->loginService = new LoginService();
        else $this->loginService = $loginService;

        if (!$view) $this->view = new View();
        else $this->view = $view;

    }

    public function getPage1()
    {
        $pagename = 'page1';
        $this->doAction($pagename, "hasPage1Rol");
    }


    public function getPage2()
    {
        $pagename = 'page2';
        $this->doAction($pagename, "hasPage2Rol");
    }


    public function getPage3()
    {
        $pagename = 'page3';
        $this->doAction($pagename, "hasPage3Rol");
    }


    public function postLogin($params = null)
    {
        if (empty($params)) $params = $_POST;
        $uri = $params['uri'];
        try {
            $this->loginService->login($params['username'], $params['password']);
            $this->view->redirect($uri);
        } catch (DomainException $e) {
            echo $this->view->render("login", array("errorMsg" => 'login incorrecto', 'uri' => $uri));
        }
        catch (InvalidArgumentException $e) {
            echo $this->view->render("login", array("errorMsg" => "username o password no pueden ser nulos", 'uri' => $uri));
        }
    }

    public function postLogout($params = null)
    {
        if (empty($params)) $params = $_POST;
        $uri = $params['uri'];
        $this->loginService->logout();
        $this->view->redirect($uri);
    }


    /**
     * @param $pagename
     */
    private function doAction($pagename, $rolCheckMethod)
    {

        if ($user = $this->loginService->existUserSession()) {
            if (($user->isAdmin()) || ($user->$rolCheckMethod())) {
                echo $this->view->render("welcome", array('pagename' => $pagename, 'username' => $user->getUsername()));
            } else {
                echo $this->view->render("forbidden", array('uri' => "/" . $pagename));
            }
        } else {
            echo $this->view->render("login", array("uri" => "/" . $pagename, 'errorMsg' => null));
        }
    }


}