<?php


namespace App\Http\Controllers\API;


use App\Core\Request;
use App\Http\Controllers\BaseController;
use App\Services\JWTService;
use App\Services\UserService;

abstract class ApiBaseController extends BaseController
{

    public $user;

    /**
     * @param $data
     */
    protected function APIResponse($data)
    {
        die(json_encode($data));
    }

    /**
     * @param UserService $userService
     * @throws \Lindelius\JWT\Exception\InvalidJwtException
     * @throws \Lindelius\JWT\Exception\JwtException
     */
    protected function verifyToken(UserService $userService)
    {
        $headers = getallheaders();
        $token = $headers['token'] ?? [];
        if (empty($token) || !is_string($token)) {
            $this->APIResponse(['success' => false, 'token' => 'Token is missing.']);
        }
        $this->user = $userService->checkToken($token);
        if (empty($this->user)) {
            $this->APIResponse(['success' => false, 'error' => 'No user found with this token.']);
        }

        $decodedJwt = JWTService::decode($token);
        try {
            $verify = $decodedJwt->verify($this->user['password'].$this->user['email']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            $verify = false;
        }

        if (!$verify) {
            $this->APIResponse(['success' => false, 'token' => 'Either token expired or invalid']);
        }
    }
}