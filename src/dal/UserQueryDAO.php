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
        $sql = "select username, roles from users";
        list($result, $item) = $this->exerciseQuery($sql);
        $users = array();
        do {
            $users[] = ViewUser::fromArray($item);
        } while ($item = $result->fetchArray(SQLITE3_ASSOC));
        return $users;
    }

    public function readById($id)
    {
        $sql = "select username, roles from users where username = :username";
        list($result, $item) = $this->exerciseQuery($sql, new User($id, "dummy", array(ViewUser::PAGE_1)));
        return ViewUser::fromArray($item);
    }


    public function readByIdWithPassword($id)
    {
        $sql = "select username, roles, password from users where username = :username";
        list($result, $item) = $this->exerciseQuery($sql, new User($id, "dummy", array(1)));
        return new User($item['username'], $item['password'], explode(",",$item['roles']));
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