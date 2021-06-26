<?php

require_once __DIR__.'/config/bootstrap.php';

if (!isset($_SESSION) || empty($_SESSION)) {
    session_start();
}