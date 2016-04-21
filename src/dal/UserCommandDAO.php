<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 10:39
 * To change this template use File | Settings | File Templates.
 */
require_once  __DIR__."/ICommandDAO.php";
require_once  __DIR__."/BaseDAO.php";
class UserCommandDAO extends BaseDAO implements ICommandDAO
{
    public function truncateTable()
    {
        $this->sqlite->exec("delete from users");
    }

    public function create($model)
    {

        $sql = 'INSERT INTO Users (username, password, roles) VALUES (:username,:password,:rol)';
        return $this->doCommand($sql, $model);

    }


    public function update($model)
    {
        $sql = 'UPDATE Users set password=:password,roles=:rol where username=:username';
        return $this->doCommand($sql, $model);

    }


    public function delete($model)
    {
        $sql = 'DELETE FROM Users where username = :username';
        return $this->doCommand($sql, $model);
    }


}