<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllWithAuthorsNative(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT a.id, a.title, a.description, u.username
            FROM article a
            JOIN user u ON a.author_id = u.id
        ';

        return $conn->fetchAllAssociative($sql);
    }
}
