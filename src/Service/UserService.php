<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator
    ) {}

    // TODO: after authentication is implemented, make $password non-nullable
    public function validateAndFlush(string $username, string $firstName, string $lastName, ?string $password = null, array $roles = []): User
    {
        $user = (new User())
            ->setUsername($username)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPassword($password ?? 'dummy')
            ->setRoles($roles);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
