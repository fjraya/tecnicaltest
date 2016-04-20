<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 12:32
 * To change this template use File | Settings | File Templates.
 */
interface IQueryDAO
{
    public function readAll();
    public function readById($id);
}