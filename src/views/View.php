<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 20/04/16
 * Time: 19:20
 * To change this template use File | Settings | File Templates.
 */

class View
{
    public function render($file, $variables = array())
    {
        extract($variables);
        ob_start();
        include __DIR__ . "/templates/" . $file . ".php";
        $renderedView = ob_get_clean();
        return $renderedView;
    }
}