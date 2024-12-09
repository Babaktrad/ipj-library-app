<?php

namespace App\Contracts\Auth;

use App\Contracts\EntitySchema;
use \Illuminate\Http\Request;

class AuthLoginSchema extends EntitySchema
{
    /**
     * Construct a new instance.
     * 
     * @param string $email
     * @param string $password
     */
    private function __construct(
        public readonly string $email,
        public readonly string $password
    ) {

    }

    /**
     * Create an instance of user login schema.
     * 
     * @param string $email
     * @param string $password
     * @return AuthLoginSchema
     */
    public static function create(string $email, string $password): self
    {
        return new self($email, $password);
    }

    /**
     * Create an instance of user login schema from request.
     * 
     * @param Request $request
     * @return AuthLoginSchema
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('password')
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
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}