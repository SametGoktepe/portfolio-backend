<?php

namespace Src\Domain\Auth\Services;

use App\Models\User;
use Src\Domain\Auth\ValueObjects\Email;
use Src\Domain\Auth\ValueObjects\Password;
use Src\Domain\Auth\ValueObjects\Username;
use Src\Domain\Shared\Exceptions\DomainException;
use Illuminate\Support\Facades\Hash;

final class AuthService
{
    public function register(array $data): array
    {
        // Validate using value objects
        $emailVO = new Email($data['email']);
        $usernameVO = new Username($data['username']);
        $passwordVO = new Password($data['password']);

        // Check if email already exists
        if (User::where('email', $emailVO->value())->exists()) {
            throw new DomainException('Email already exists');
        }

        // Check if username already exists
        if (User::where('username', $usernameVO->value())->exists()) {
            throw new DomainException('Username already exists');
        }

        // Create user
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'] ?? null,
            'username' => $usernameVO->value(),
            'email' => $emailVO->value(),
            'password' => Hash::make($passwordVO->value()),
        ]);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(string $email, string $password): array
    {
        // Validate email format
        $emailVO = new Email($email);
        $passwordVO = new Password($password);

        // Find user
        $user = User::where('email', $emailVO->value())->first();

        if (!$user) {
            throw new DomainException('Invalid credentials');
        }

        // Verify password
        if (!Hash::check($passwordVO->value(), $user->password)) {
            throw new DomainException('Invalid credentials');
        }

        // Revoke old tokens (optional - single device login)
        // $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        // Revoke current token
        $user->tokens()->delete();
    }

    public function logoutAll(User $user): void
    {
        // Revoke all tokens
        $user->tokens()->delete();
    }

    public function me(User $user): User
    {
        return $user;
    }
}

