<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 21/04/16
 * Time: 11:43
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/IUserService.php";
require_once __DIR__ . "/../dal/UserCommandDAO.php";
require_once __DIR__ . "/../dal/UserQueryDAO.php";
require_once __DIR__ . "/../dal/SQLiteConstant.php";

class UserService implements IUserService
{
    private $userCommandDAO;
    private $userQueryDAO;

    public function __construct(ICommandDAO $userCommandDAO = null, IUserQueryDAO $userQueryDAO = null)
    {
        if (!$userCommandDAO) $this->userCommandDAO = new UserCommandDAO(SQLiteConstant::SQLITE_RESOURCE);
        else $this->userCommandDAO = $userCommandDAO;

        if (!$userQueryDAO) $this->userQueryDAO = new UserQueryDAO(SQLiteConstant::SQLITE_RESOURCE);
        else $this->userQueryDAO = $userQueryDAO;
    }

    public function listUsersByUser($user)
    {
        if ($user->isAdmin()) {
            $users = $this->userQueryDAO->readAll();
        } else {
            $users = array($this->userQueryDAO->readById($user->getUsername()));
        }
        return $users;
    }

    public function createUser($user, $inputUser)
    {
        if ($user->isAdmin()) {
            try {
                $this->userQueryDAO->readById($inputUser->getUsername());
                throw new InvalidArgumentException("user con username " . $inputUser->getUsername() . " ya existe");
            } catch (DomainException $e) {
                $this->userCommandDAO->create($inputUser);
            }
        } else throw new InvalidArgumentException("No tiene permisos de admin para crear usuarios");
    }


    public function updateUser($user, $username, $password, $roles)
    {
        if ($user->isAdmin()) {
            $user = $this->userQueryDAO->readByIdWithPassword($username);
            if ($password) $user->setPassword($password);
            if ($roles) $user->setRoles(explode(",", $roles));
            $this->userCommandDAO->update($user);
        } else throw new InvalidArgumentException("No tiene permisos de admin para modificar usuarios");
    }
}