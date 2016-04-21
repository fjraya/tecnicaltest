<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 13:05
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/IParser.php";
class JSONParser implements IParser
{

    public function getName()
    {
        return "json";
    }


    public function parse(array $items = null)
    {
        if (empty($items)) return "[]";
        return json_encode($items);
    }
}