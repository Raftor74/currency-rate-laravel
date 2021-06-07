<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Auth\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param string $email
     * @param string $password
     * @return string
     * @throws InvalidCredentialsException
     */
    public function retrieveToken(string $email, string $password): string
    {
        /** @var User $user */
        $user = User::query()->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException('The provided credentials are incorrect');
        }

        return $user->createToken('api')->plainTextToken;
    }
}
