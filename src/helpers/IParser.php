<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 13:00
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/IParser.php";
interface IParser
{
    public function parse(array $items = null);
}