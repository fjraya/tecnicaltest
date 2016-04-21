<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 13:02
 * To change this template use File | Settings | File Templates.
 */

class ViewUser
{
    const PAGE_1 = 1;
    const PAGE_2 = 2;
    const PAGE_3 = 3;
    const ADMIN = 4;
    private $username;
    private $roles;

    public function __construct($username, $roles)
    {
        if (!$username) $this->throwException("username");
        if (empty($roles)) $this->throwException("roles");
        if (!is_array($roles)) throw new InvalidArgumentException("Roles debe ser un array");
        $this->username = $username;
        $this->roles = $roles;
    }

    public static function fromArray($items)
    {
        if (empty($items)) throw new DomainException("No se puede construir un ViewUser con array vacío");
        return new ViewUser($items['username'], explode(",",$items['roles']));
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }


    public function toString()
    {
        $roles = implode(",", $this->roles);
        return "user:" . $this->username . ",roles:" . $roles;
    }


    public function hasPage1Rol()
    {

        return in_array(ViewUser::PAGE_1, $this->roles);
    }

    public function hasPage2Rol()
    {
        return in_array(ViewUser::PAGE_2, $this->roles);
    }

    public function hasPage3Rol()
    {
        return in_array(ViewUser::PAGE_3, $this->roles);
    }

    public function isAdmin()
    {
        if (count($this->roles) > 1) return false;
        return in_array(ViewUser::ADMIN, $this->roles);
    }


    protected function throwException($fieldName)
    {
        throw new DomainException("El $fieldName no puede estar vacío");
    }
}