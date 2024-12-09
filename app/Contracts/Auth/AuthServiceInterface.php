<?php

namespace App\Contracts\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Contracts\Auth\AuthRegisterSchema;
use App\Contracts\Auth\AuthLoginSchema;

interface AuthServiceInterface
{
    /**
     * Register a new user.
     * 
     * @param AuthRegisterSchema $request
     * @return Authenticatable
     */
    public function register(AuthRegisterSchema $request): Authenticatable;

    /**
     * Login a user.
     * 
     * @param AuthLoginSchema $data
     * @return null|Authenticatable
     */
    public function login(AuthLoginSchema $data): null|Authenticatable;

    /**
     * Logout a logged in user.
     * 
     * @param Authenticatable $user
     * @return void
     */
    public function logout(Authenticatable $user);

    /**
     * Get the logged in user.
     * 
     * @return Authenticatable
     */
    public function user(): Authenticatable;

    /**
     * Check if there is a logged in user.
     * 
     * @return bool
     */
    public function check(): bool;
}