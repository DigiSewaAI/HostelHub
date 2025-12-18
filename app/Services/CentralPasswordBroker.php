<?php

namespace App\Services;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;

class CentralPasswordBroker implements PasswordBrokerContract
{
    protected $tokens;
    protected $users;

    public function __construct(DatabaseTokenRepository $tokens, $users)
    {
        $this->tokens = $tokens;
        $this->users = $users;
    }

    public function sendResetLink(array $credentials)
    {
        // Force central DB connection
        config(['database.default' => 'mysql']);
        
        $user = $this->getUser($credentials);
        
        if (is_null($user)) {
            return PasswordBrokerContract::INVALID_USER;
        }
        
        $user->sendPasswordResetNotification(
            $this->tokens->create($user)
        );
        
        return PasswordBrokerContract::RESET_LINK_SENT;
    }

    public function reset(array $credentials, \Closure $callback)
    {
        config(['database.default' => 'mysql']);
        
        $user = $this->validateReset($credentials);
        
        if (!$user instanceof CanResetPasswordContract) {
            return $user;
        }
        
        $password = $credentials['password'];
        $callback($user, $password);
        $this->tokens->delete($user);
        
        return PasswordBrokerContract::PASSWORD_RESET;
    }

    protected function validateReset(array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return PasswordBrokerContract::INVALID_USER;
        }
        
        if (!$this->tokens->exists($user, $credentials['token'])) {
            return PasswordBrokerContract::INVALID_TOKEN;
        }
        
        return $user;
    }

    public function getUser(array $credentials)
    {
        $credentials = array_filter($credentials, function ($key) {
            return !in_array($key, ['token', 'password', 'password_confirmation']);
        }, ARRAY_FILTER_USE_KEY);
        
        $user = $this->users->retrieveByCredentials($credentials);
        
        if ($user && !$user instanceof CanResetPasswordContract) {
            throw new \UnexpectedValueException('User must implement CanResetPassword interface.');
        }
        
        return $user;
    }

    public function createToken(CanResetPasswordContract $user)
    {
        return $this->tokens->create($user);
    }

    public function deleteToken(CanResetPasswordContract $user)
    {
        $this->tokens->delete($user);
    }

    public function tokenExists(CanResetPasswordContract $user, $token)
    {
        return $this->tokens->exists($user, $token);
    }

    public function getRepository()
    {
        return $this->tokens;
    }
}