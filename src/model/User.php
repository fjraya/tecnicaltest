<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 19/04/16
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */
require_once  __DIR__."/ViewUser.php";
class User extends ViewUser
{

    protected $password;


    public function __construct($username, $password, $rol)
    {
       parent::__construct($username, $rol);
        if (!$password) $this->throwException("password");
        $this->password = $password;

    }

    public function getPassword()
    {
        return $this->password;
    }

    public function toString()
    {
        return parent::toString().",password:".$this->password;
    }

    public function hasSamePassword($password)
    {
        if ($password == null) throw new InvalidArgumentException("Password no puede ser vacío");
        return md5($password) == $this->getPassword();
    }


}