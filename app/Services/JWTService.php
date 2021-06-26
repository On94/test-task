<?php


namespace App\Services;

use Lindelius\JWT\Algorithm\HMAC\HS256;
use Lindelius\JWT\JWT;

/**
 * Class JWTService
 * @package App\Services
 */
class JWTService extends JWT
{
    use HS256;
    public static $leeway = 60;

}