<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 12:35
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . "/IUserQueryDAO.php";
require_once  __DIR__."/BaseDAO.php";
require_once __DIR__ . "/../model/ViewUser.php";
require_once __DIR__ . "/../model/User.php";

class UserQueryDAO extends BaseDAO implements IUserQueryDAO
{

    public function readAll()
    {
        $sql = "select username, rol from users";
        list($result, $item) = $this->exerciseQuery($sql);
        $users = array();
        do {
            $users[] = new ViewUser($item['username'], $item['rol']);
        } while ($item = $result->fetchArray(SQLITE3_ASSOC));
        return $users;
    }

    public function readById($id)
    {
        $sql = "select username, rol from users where username = :username";
        list($result, $item) = $this->exerciseQuery($sql, new User($id, "dummy", "dummy"));
        return new ViewUser($item['username'], $item['rol']);
    }


    public function readByIdWithPassword($id)
    {
        $sql = "select username, rol, password from users where username = :username";
        list($result, $item) = $this->exerciseQuery($sql, new User($id, "dummy", "dummy"));
        return new User($item['username'], $item['password'], $item['rol']);
    }


    /**
     * @param $sql
     * @return array
     * @throws DomainException
     */
    private function exerciseQuery($sql, $model = null)
    {
        $result = $this->doCommand($sql, $model);
        $item = $result->fetchArray(SQLITE3_ASSOC);
        if (!$item) throw new DomainException("No hay usuarios en la bbdd");
        return array($result, $item);
    }
}