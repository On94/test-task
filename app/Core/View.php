<?php

namespace App\Core;

/**
 * Class View
 * @package Core
 */
class View
{
    /**
     * @param $file
     * @param array $params
     */
    public function render($file, $params = []):void
    {
        $path = __DIR__ . "/../../resources/views/" . $file;
        if (is_file($path)) {
            echo require_once $path;
        } else {
            echo 'No file exist ' . $path;
        }
        die;
    }
}