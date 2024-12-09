<?php

namespace App\Services\Api\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\Contracts\Auth\AuthLoginSchema;
use App\Contracts\Auth\AuthRegisterSchema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SanctumAuthService implements AuthServiceInterface
{
    /**
     * Register a user over laravel sanctum.
     * 
     * @param AuthRegisterSchema $request
     * @return User
     */
    public function register(AuthRegisterSchema $request): User
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    /**
     * Login a user over laravel sanctum.
     * 
     * @param AuthLoginSchema $request
     * @return null|User
     */
    public function login(AuthLoginSchema $request): null|User
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $user['token'] = $user->createToken(config('sanctum.phrase'))->plainTextToken;
            return $user;
        }

        return null;
    }

    /**
     * Logout a user over laravel sanctum.
     * 
     * @param \Illuminate\Foundation\Auth\User $user
     * @return void
     */
    public function logout(Authenticatable $user)
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Check if there is a logged in user over laravel sanctum.
     * 
     * @return bool
     */
    public function check(): bool
    {
        return Auth::check();
    }

    /**
     * Get the logged in user over laravel sanctum.
     * 
     * @return \Illuminate\Foundation\Auth\User
     */
    public function user(): Authenticatable
    {
        return Auth::user();
    }
}