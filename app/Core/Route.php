<?php

namespace App\Core;

use Src\DI\DI;
use Src\DI\RouteInterface;

/**
 * Class Route
 * @method get(string $name, array $arguments)
 * @method post(string $name, array $arguments)
 * @method put(string $name, array $arguments)
 * @method delete(string $name, array $arguments)
 * @method patch(string $name, array $arguments)
 * @package Core
 */
class Route implements RouteInterface
{
    /**
     * @var array
     */
    protected static $methods = ['get', 'post', 'put', 'patch', 'delete'];

    /**
     * @var string
     */
    public static $controller;

    /**
     * @var string
     */

    public static $name;

    /**
     * @var  string
     */
    public static $action;

    /**
     * Set headers
     */
    public static function initializeRESTApi()
    {
        header('Content-Type: application/json');
    }

    /**
     * @param $name
     * @param array $arguments
     * @return bool
     * @throws \ReflectionException
     */
    public static function __callStatic($name, array $arguments): bool
    {
        if (empty($_SERVER['REQUEST_METHOD']) || !in_array($name, self::$methods) || $_SERVER['REQUEST_METHOD'] != strtoupper($name)) {
            return false;
        }
        $path = $arguments[0];
        array_shift($arguments);
        return self::init($path, ...$arguments);
    }

    /**
     * @param string $path
     * @param array $arguments
     * @return bool
     * @throws \ReflectionException
     */
    private static function init(string $path, array $arguments = []): bool
    {
        list($controller, $action) = $arguments;
        self::$controller = $controller;
        self::$action = $action;
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $route = $uri[0];
        if ($route == $path) {
            $controller = self::$controller;
            $action = self::$action;

            /// Handle Constructor Arguments
            $class = new \ReflectionClass(self::$controller);
            $constructorReflection = $class->getConstructor();
            $constructPrams = null;
            if ($constructorReflection) {
                $constructPrams = $constructorReflection->getParameters();
            }
            ///---------------------

            $constructorNewParams = [];
            array_map(function (\ReflectionParameter $param) use (&$constructorNewParams) {
                $className = $param->getClass()->getName();
                if (class_exists($className)) {
                    $constructorNewParams[] = new $className;
                }
            }, $constructPrams);

            $controller = empty($constructorNewParams) ? new $controller : new $controller(...$constructorNewParams);
            $r = new \ReflectionMethod($controller, $action);
            $params = $r->getParameters();
            $reflectionParams = [];
            foreach ($params as $param) {
                $className = $param->getClass()->getName();
                if (class_exists($className)) {
                    DI::make($className);
                    $reflectionParams[] = DI::get($className);
                    continue;
                }
                $reflectionParams[] = $className;
            }

            $controller->$action(...$reflectionParams);
        }
        return true;
    }
}