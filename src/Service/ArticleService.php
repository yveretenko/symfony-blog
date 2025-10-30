<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ArticleService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator
    ) {}

    // TODO: after authentication is implemented, make $author non-nullable
    public function validateAndFlush(string $title, string $description, User $author = null): Article
    {
        $article = (new Article())
            ->setTitle($title)
            ->setDescription($description)
            ->setAuthor($author);

        $errors = $this->validator->validate($article);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
}
