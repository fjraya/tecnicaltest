<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 16:17
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/IQueryDAO.php";
interface IUserQueryDAO extends IQueryDAO
{
    public function readByIdWithPassword($id);
}