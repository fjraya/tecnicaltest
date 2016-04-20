<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 15:10
 * To change this template use File | Settings | File Templates.
 */

interface ISessionWrapper
{
    public function write($key, $value);
    public function read($key);
    public function delete($key);
    public function destroy();
}