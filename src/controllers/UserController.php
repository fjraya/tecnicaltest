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

    public function page1()
    {
        $pagename = 'page1';
        $this->doAction($pagename, "hasPage1Rol");
    }


    public function page2()
    {
        $pagename = 'page2';
        $this->doAction($pagename, "hasPage2Rol");
    }


    public function page3()
    {
        $pagename = 'page3';
        $this->doAction($pagename, "hasPage3Rol");
    }


    public function login($params = null)
    {
        if (!$params) $params = $_POST;
        try {
            $this->loginService->login($params['username'], $params['password']);
            header("Location: ".$params['uri']);
        }
        catch(DomainException $e)
        {
            echo $this->view->render("login", array("errorMsg"=>'login incorrecto', 'uri'=>$params['uri']));
        }
    }

    public function logout()
    {
        $this->loginService->logout();

    }


    /**
     * @param $pagename
     */
    private function doAction($pagename, $rolCheckMethod)
    {

        if ($user = $this->loginService->existUserSession()) {
            if (($user->isAdmin())||($user->$rolCheckMethod())) {
                echo $this->view->render("welcome", array('pagename' => $pagename, 'username' => $user->getUsername()));
            } else {
                echo $this->view->render("forbidden");
            }
        } else {
            echo $this->view->render("login", array("uri" => "/".$pagename, 'errorMsg'=>null));
        }
    }


}