<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 14:50
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . "/ISessionWrapper.php";
class SessionWrapper implements ISessionWrapper
{

    protected $SESSION_AGE = 300;




    public function dump() //For test purposes
    {
        return json_encode($_SESSION);
    }


    public function write($key, $value)
    {
        $this->checkKeyArgument($key);
        $this->init();
        $_SESSION[$key] = $value;
        $this->manageExpiration();
        echo var_export($_SESSION, true);
        return $value;
    }


    public function read($key)
    {
        $this->checkKeyArgument($key);
        $this->init();
        if (isset($_SESSION[$key])) {
            $this->manageExpiration();
            return $_SESSION[$key];
        }
        return false;
    }


    public function delete($key)
    {
        $this->checkKeyArgument($key);
        $this->init();
        unset($_SESSION[$key]);
        $this->manageExpiration();
    }

    protected function manageExpiration()
    {
        $last = isset($_SESSION['LAST_ACTIVE']) ? $_SESSION['LAST_ACTIVE'] : false;

        if ($this->isExpired($last)) {
            $this->destroy();
        }
        $_SESSION['LAST_ACTIVE'] = time();
    }


    public function destroy()
    {
        $_SESSION = array();
        if ('' !== session_id()) {
            session_destroy();
        }
    }


    protected function init()
    {
        session_start();
    }

    /**
     * @param $key
     * @throws InvalidArgumentException
     */
    private function checkKeyArgument($key)
    {
        if (!is_string($key))
            throw new InvalidArgumentException('Key debe ser un string');
    }

    /**
     * @param $last
     * @return bool
     */
    protected function isExpired($last)
    {
        return false !== $last && (time() - $last > $this->SESSION_AGE);
    }
}