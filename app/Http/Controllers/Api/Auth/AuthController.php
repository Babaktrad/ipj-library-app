<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\Contracts\Auth\AuthRegisterSchema;
use App\Contracts\Auth\AuthLoginSchema;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Http\Requests\Api\Auth\ApiRegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiBaseController
{

    public function __construct(protected AuthServiceInterface $authService)
    {
        //
    }

    /**
     * Register a user over api.
     * 
     * @param ApiRegisterRequest $request
     * @return JsonResponse
     */
    public function register(ApiRegisterRequest $request): JsonResponse
    {
        try {
            $data = AuthRegisterSchema::fromRequest($request);

            $this->authService->register($data);

            return $this->succeeded();
        } catch (\Throwable $e) {
            return $this->failed();
        }
    }

    /**
     * Login a user over api.
     * 
     * @param ApiLoginRequest $request
     * @return JsonResponse
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $data = AuthLoginSchema::fromRequest($request);

        $user = $this->authService->login($data);

        if ($user) {
            $response = new AuthResource($user);

            return $this->succeeded($response, __('messages.auth.login.succeeded'));
        } else {
            return $this->failed();
        }
    }

    /**
     * Logout a user over api.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            if ($this->authService->check()) {
                $this->authService->logout($this->authService->user());
            }

            return $this->succeeded(message: __('messages.auth.logout.succeeded'));
        } catch (\Throwable $e) {
            return $this->failed();
        }
    }
}
