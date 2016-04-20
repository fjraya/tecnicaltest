<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyeos
 * Date: 20/04/16
 * Time: 12:37
 * To change this template use File | Settings | File Templates.
 */

class BaseDAO
{
    protected $sqlite;

    public function __construct($name, $sqlite = null)
    {
        if (!$sqlite) $this->sqlite = new SQLite3($name);
        else $this->sqlite = $sqlite;
    }


    /**
     * @param $model
     * @param $sql
     * @param $error
     * @throws Exception
     * @throws DomainException
     */
    public function doCommand($sql, $model = null)
    {
        $sentence = $this->sqlite->prepare($sql);
        if ($model) {
            $sentence->bindValue(':username', $model->getUsername(), SQLITE3_TEXT);
            $sentence->bindValue(':password', md5($model->getPassword()), SQLITE3_TEXT);
            $sentence->bindValue(':rol', $model->getRol(), SQLITE3_INTEGER);
        }
        try {
            $result = $sentence->execute();
        } catch (Exception $e) {
            throw new DomainException($e->getMessage());
        }
        if (!$result) {
            throw new Exception("Error en doCommand");
        }
        return $result;
    }


}