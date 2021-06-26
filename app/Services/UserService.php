<?php

namespace App\Services;
use App\Repositories\UserRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function register(array $data):bool
    {
        return $this->repository->register($data);
    }

    /**
     * @param array $data
     * @param bool $dump
     * @return array
     * @throws \Lindelius\JWT\Exception\JwtException
     * @throws \Lindelius\JWT\Exception\UnsupportedAlgorithmException
     */
    public function login(array $data, bool $dump = false):array
    {
        $response = [
             'success' => false
        ];

        $jwt = JWTService::create('HS256');

        // Let the JWT expire after 20 minutes (optional, but recommended)
        $jwt->exp = time() + (60 * 20);

        // Encode the JWT using a key suitable for the chosen algorithm
        $encodedJwtHash = $jwt->encode(sha1($data['password']) . $data['email']);
        $response['success'] = $this->repository->saveToken($encodedJwtHash, $data['email'], $data['password']);

        if ($response['success']) {
            $response['token'] = $encodedJwtHash;
        } else {
            $response['error'] = 'Wrong credentials was set, check again.';
        }

        if ($dump === true) {
            die(json_encode($response));
        }

        return $response;
    }

    /**
     * @param string $token
     * @return array
     */
    public function checkToken(string $token):array
    {
        try {
            return $this->repository->checkToken($token);
        } catch (NonUniqueResultException $e) {
        }
    }

}