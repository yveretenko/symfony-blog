<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private string $apiKey
    ) {}

    // TODO: after authentication is implemented, make $password non-nullable
    public function createAndFlush(string $username, string $firstName, string $lastName, ?string $password = null): User
    {
        $user = (new User())
            ->setUsername($username)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPassword($password ?? 'dummy');

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
