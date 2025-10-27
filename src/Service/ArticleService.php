<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class ArticleService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createAndFlush(string $title, string $description): Article
    {
        $article = (new Article())
            ->setTitle($title)
            ->setDescription($description);

        // temporary dummy author until authentication
        $dummyAuthor = $this->em->getRepository(User::class)->find(1);

        if (!$dummyAuthor) {
            // fallback in case user with ID 1 doesn’t exist yet
            $dummyAuthor = (new User())
                ->setUsername('dummy')
                ->setFirstName('Dummy')
                ->setLastName('Author')
                ->setPassword('dummy');

            $this->em->persist($dummyAuthor);
        }

        $article->setAuthor($dummyAuthor);

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
}
