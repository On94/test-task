<?php

namespace Src\DI;

/**
 * Interface RouteInterface
 * @package Src\DI
 */
interface RouteInterface
{
    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, array $arguments);
}