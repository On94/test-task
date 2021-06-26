<?php

namespace Src\DI;

/**
 * Interface DIBuilderInterface
 * @package Src\DI
 */
interface DIBuilderInterface
{
    /**
     * @param string $class
     * @return mixed
     */
    public static function make(string $class);

    /**
     * @param string $class
     * @return mixed
     */
    public static function get(string $class);
}