<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 11:43
 * To change this template use File | Settings | File Templates.
 */


interface IUserService
{
    public function listUsersByUser($user);
    public function createUser($user, $inputUser);
    public function updateUser($user, $username, $password, $roles);
}