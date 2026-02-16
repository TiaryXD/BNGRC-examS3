<?php
namespace app\services;

use app\repositories\UserRepository;

class UserService
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function register(array $values, $plainPassword)
    {
        $hash = password_hash((string) $plainPassword, PASSWORD_DEFAULT);
        return $this->repo->create(
            $values['email'],
            $hash,
        );
    }
}
