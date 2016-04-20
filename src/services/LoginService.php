<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 16:05
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/ILoginService.php";
require_once __DIR__ . "/../dal/IUserQueryDAO.php";
require_once __DIR__ ."/../helpers/SessionWrapper.php";
class LoginService implements ILoginService
{
    private $userQueryDAO;
    private $sessionWrapper;

    public function __construct(IUserQueryDAO $userQueryDAO = null, ISessionWrapper $sessionWrapper = null)
    {
        if (!$userQueryDAO) $this->userQueryDAO = new UserQueryDAO("polla");
        else $this->userQueryDAO = $userQueryDAO;

        if (!$sessionWrapper) $this->sessionWrapper = new SessionWrapper();
        else $this->sessionWrapper = $sessionWrapper;
    }

    public function login($username, $password)
    {
        $user = $this->userQueryDAO->readByIdWithPassword($username);
        if ($user->hasSamePassword($password)) {
            $this->sessionWrapper->write('username', $user->getUsername());
            $this->sessionWrapper->write('rol', $user->getRol());
            return $user;
        } else throw new DomainException("Password inválido");
    }
}