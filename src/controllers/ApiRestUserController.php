<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 10:19
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/../../src/services/UserService.php";
require_once __DIR__ . "/../../src/helpers/NullSessionWrapper.php";
require_once __DIR__ . "/../../src/helpers/JSONParser.php";
class ApiRestUserController
{

    private $loginService;
    private $userService;
    private $user;
    protected $vars;
    protected $parser;


    public function __construct($vars, ILoginService $loginService = null, IUserService $userService = null, IParser $parser = null)
    {
        if (!$loginService) $this->loginService = new LoginService(null, new NullSessionWrapper());
        else $this->loginService = $loginService;

        if (!$userService) $this->userService = new UserService();
        else $this->userService = $userService;

        if (!$parser) $this->parser = new JSONParser();
        else $this->parser = $parser;

        $this->user = $this->doLogin($vars);
        $this->vars = $vars;
    }


    public function getUsers()
    {
        $result = array();
        $header = "HTTP/1.0 200 OK";
        try {
            $result = $this->userService->listUsersByUser($this->user);
            $result = array('status' => '200 OK', 'data' => $this->toArray($result));
        } catch (Exception $e) {
            $result = array('status' => '409 Conflict', 'message' => $e->getMessage());
            $header = 'HTTP/1.0 409 Conflict';
        }
        $this->response($header, $result);
    }


    public function setVars($vars) //For test purposes
    {
        $this->vars = $vars;
    }

    public function postUsers()
    {
        $result = array('status' => '201 Created', 'message' => 'usuario creado correctamente');
        $header = "HTTP/1.0 201 Created";
        try {
            $user = new User($this->vars['username'], $this->vars['password'], explode(",", $this->vars['roles']));
            $this->userService->createUser($this->user, $user);

        } catch (Exception $e) {
            $result = array('status' => '403 Forbidden', 'message' => $e->getMessage());
            $header = 'HTTP/1.0 403 Forbidden';
        }
        $this->response($header, $result);
    }


    public function putUsers()
    {
        $result = array('status' => '201 Created', 'message' => 'usuario modificado correctamente');
        $header = "HTTP/1.0 201 Created";

        try {
            $this->userService->updateUser($this->user, $this->vars['username'], $this->vars['password'], $this->vars['roles']);
        } catch (Exception $e) {
            $result = array('status' => '403 Forbidden', 'message' => $e->getMessage());
            $header = 'HTTP/1.0 403 Forbidden';
        }
        $this->response($header, $result);
    }


    private function toArray($items)
    {
        $prefix = "user";
        $result = array();
        $index = 1;
        foreach ($items as $item) {
            $result[$prefix . $index] = $item->toArray();
            $index++;
        }
        return $result;
    }


    /**
     * @param $vars
     * @return User
     */
    private function doLogin($vars)
    {
        
        $username = $vars['auth_username'];
        $password = $vars['auth_password'];
        try {
            $user = $this->loginService->login($username, $password);
            return $user;
        } catch (DomainException $e) {
            $message = array('status'=>'403 Forbidden', 'message' => "login incorrecto");
            $this->response('HTTP/1.0 403 Forbidden',$message);
        }
        catch (InvalidArgumentException $e) {
            $message = array('status'=>'403 Forbidden', 'message' => "se requiere un user y un password");
            $this->response('HTTP/1.0 403 Forbidden',$message);
        }
    }


    protected function response($head, $message)
    {
        header($head);
        header("Content-Type: application/".$this->parser->getName());
        echo $this->parser->parse($message);
        die();
    }

    public function getName()
    {
        return "apiRest|".$this->parser->getName();
    }


}