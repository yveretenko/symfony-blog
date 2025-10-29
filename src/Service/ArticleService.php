<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class ArticleService
{
    public function __construct(private EntityManagerInterface $em) {}

    // TODO: after authentication is implemented, make $author non-nullable
    public function createAndFlush(string $title, string $description, User $author = null): Article
    {
        $article = (new Article())
            ->setTitle($title)
            ->setDescription($description)
            ->setAuthor($author);

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
}
