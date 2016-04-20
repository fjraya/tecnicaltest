<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 14:50
 * To change this template use File | Settings | File Templates.
 */
session_start();
require_once __DIR__ . "/ISessionWrapper.php";
class SessionWrapper implements ISessionWrapper
{

    protected $SESSION_AGE = 300;
    protected $sessionStorage;

    public function __construct($session = null)
    {
        if (!$session) $this->sessionStorage = $_SESSION;
        else $this->sessionStorage = $session;
    }


    public function dump() //For test purposes
    {
        return json_encode($this->sessionStorage);
    }


    public function write($key, $value)
    {
        $this->checkKeyArgument($key);
        $this->init();
        $this->sessionStorage[$key] = $value;
        $this->manageExpiration();
        return $value;
    }


    public function read($key)
    {
        $this->checkKeyArgument($key);
        $this->init();
        if (isset($this->sessionStorage[$key])) {
            $this->manageExpiration();
            return $this->sessionStorage[$key];
        }
        return false;
    }


    public function delete($key)
    {
        $this->checkKeyArgument($key);
        $this->init();
        unset($this->sessionStorage[$key]);
        $this->manageExpiration();
    }

    protected function manageExpiration()
    {
        $last = isset($this->sessionStorage['LAST_ACTIVE']) ? $this->sessionStorage['LAST_ACTIVE'] : false;

        if ($this->isExpired($last)) {
            $this->destroy();
        }
        $this->sessionStorage['LAST_ACTIVE'] = time();
    }


    public function destroy()
    {
        $this->sessionStorage = array();
        if ('' !== session_id()) {
            session_destroy();
        }
    }


    protected function init()
    {
        if ('' === session_id()) {
            return session_start();
        }
        return session_regenerate_id(true);
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