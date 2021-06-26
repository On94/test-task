<?php

namespace Src\DI;

use Src\DI\Traits\DIBuilder;

/**
 * Class DI
 * @package Src\DI
 */
class DI implements DIInterface, DIBuilderInterface
{
    use DIBuilder;
}