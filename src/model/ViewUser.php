<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 13:02
 * To change this template use File | Settings | File Templates.
 */

class ViewUser
{
    const PAGE_1 = 1;
    const PAGE_2 = 2;
    const PAGE_3 = 3;
    private $username;
    private $rol;

    public function __construct($username, $rol)
    {
        if (!$username) $this->throwException("username");
        if (!$rol) $this->throwException("rol");
        $this->username = $username;
        $this->rol = $rol;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
    }


    public function toString()
    {
        return "user:" . $this->username .",rol:" . $this->rol;
    }

    protected function throwException($fieldName)
    {
        throw new DomainException("El $fieldName no puede estar vacío");
    }
}