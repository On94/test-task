<?php

namespace App\Http\Controllers\API;


use App\Core\Request;
use App\Core\Validator;
use App\Services\UserService;


/**
 * Class AuthController
 * @package App\Http\Controllers\API
 */
class AuthController extends ApiBaseController
{
    /**
     * @var Validator
     */
    private Validator $validator;

    /**
     * @var UserService
     */
    private UserService $service;

    /**
     * AuthController constructor.
     * @param Validator $validator
     * @param UserService $service
     */
    public function __construct(Validator $validator, UserService $service)
    {
        $this->validator = $validator;
        $this->service = $service;
    }

    /**
     * @param Request $request
     */
    public function register(Request $request): void
    {
        $data = $request->only(['name', 'email', 'password']);

        $this->validator->setRules([
            'name, email,password,' => ['required', 'message' => '%s cannot be blank.'],
            'email' => ['email', 'min' => 3, 'max' => 255, 'message' => '%s please fill out this  field.'],
            'name, email,password' => ['string', 'max' => 255, 'message' => 'field is not correct.'],
        ])->setData($data);

        if ($this->validator->validation()) {
            try {
                $this->service->register($data);

                $this->service->login($data, true);
            } catch (\Exception $exception) {
                self::APIResponse([
                    'message' => 'This email address is already being used',
                    'success'=>false
                ]);
            }

        }
        self::APIResponse($this->validator->getErrors());
    }

    public function login(Request $request): void
    {
        $data = $request->only(['email', 'password']);
        $this->validator->setRules([
            'email,  password' => ['required', 'message' => '%s cannot be blank.'],
            'email' => ['email', 'min' => 3, 'max' => 255, 'message' => '%s please fill out this  field.'],
            'password' => ['string', 'min' => 3, 'max' => 255, 'message' => '%s cannot be blank.'],
        ])->setData($data);

        if ($this->validator->validation()) {
            $this->service->login($data, true);
        }
        self::APIResponse($this->validator->getErrors());
    }

}






