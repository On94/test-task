<?php

namespace App\Core;

/**
 * Class Request
 * @package Core
 */
class Request
{
    /**
     * @return mixed
     */
    public function all()
    {
        return $_REQUEST;
    }

    /**
     * @param $except
     * @return array
     */
    public function only($except): array
    {
        $data = [];
        foreach ($except as $allowed) {
            if (isset($_REQUEST[$allowed])) {
                $data[$allowed] = $_REQUEST[$allowed];
            }
        }
        return $data;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function request($key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * @return array
     */
    public function put():array
    {
        $put = [];
        parse_str(file_get_contents('php://input'), $put);
        return $put;
    }
}
