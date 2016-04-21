<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 10:39
 * To change this template use File | Settings | File Templates.
 */

interface ICommandDAO
{
    public function create($model);
    public function update($model);
}