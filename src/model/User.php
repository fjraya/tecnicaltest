<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 19/04/16
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */

class User
{
    const PAGE_1 = 1;
    const PAGE_2 = 2;
    const PAGE_3 = 3;
    private $username;
    private $password;
    private $rol;

    public function __construct($username, $password, $rol)
    {
        if (!$username) $this->throwException("username");
        if (!$password) $this->throwException("password");
        if (!$rol) $this->throwException("rol");
        $this->username = $username;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function toString()
    {
        return "user:".$this->username.",password:".$this->password.",rol:".$this->rol;
    }

    private function throwException($fieldName)
    {
        throw new DomainException("El $fieldName no puede estar vacío");
    }

}