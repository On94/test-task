<?php

use App\Core\Route;


use App\Http\Controllers\ViewsController;


Route::get('/', [ViewsController::class, 'welcomePage']);
