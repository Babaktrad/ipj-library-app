<?php

namespace App\Contracts\Auth;

use App\Contracts\EntitySchema;
use Illuminate\Http\Request;

class AuthRegisterSchema extends EntitySchema
{
    /**
     * Construct a new instance.
     * 
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $password_confirmation
     */
    private function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $password_confirmation
    ) {
        //
    }

    /**
     * Create an instance of user registration schema.
     * 
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $password_confirmation
     * @return \App\Contracts\Auth\AuthRegisterSchema
     */
    public static function create(
        string $name,
        string $email,
        string $password,
        string $password_confirmation
    ): self {
        return new self(
            $name,
            $email,
            $password,
            $password_confirmation
        );
    }

    /**
     * Create an instance of user registration schema from request.
     * 
     * @param Request $request
     * @return AuthRegisterSchema
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('password_confirmation')
        );
    }

    /**
     * Convert the schema to array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ];
    }
}