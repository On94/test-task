<?php

namespace  App\Http\Controllers;

/**
 * Class ViewsController
 * @package App\Http\Controllers
 */
class ViewsController extends BaseController
{
    /**
     * Welcome Page
     */
    public function welcomePage()
    {
        self::$view->render('welcome.php');
    }

}