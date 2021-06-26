<?php

namespace App\Http\Controllers;

use App\Core\View;


/**
 * Class BaseController
 * @package App\Http\Controllers
 */
abstract class BaseController
{
    /**
     * @var View
     */
    public static View $view;


    public static function init()
    {
        self::$view = new View();
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

}

