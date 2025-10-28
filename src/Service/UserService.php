<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createAndFlush(string $username, string $firstName, string $lastName): User
    {
        $user = (new User())
            ->setUsername($username)
            ->setFirstName($firstName)
            ->setLastName($lastName);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
