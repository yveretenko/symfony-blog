<?php

namespace App\Service;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

readonly class ArticleService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createAndFlush(string $title, string $description): Article
    {
        $article = (new Article())
            ->setTitle($title)
            ->setDescription($description);

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
}
