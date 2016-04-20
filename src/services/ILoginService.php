<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 16:06
 * To change this template use File | Settings | File Templates.
 */

interface ILoginService
{
    public function login($username, $password);
}