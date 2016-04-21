<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 18:06
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/ISessionWrapper.php";
class NullSessionWrapper implements ISessionWrapper
{

    public function write($key, $value)
    {
        // TODO: Implement write() method.
    }

    public function read($key)
    {
        // TODO: Implement read() method.
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }
}