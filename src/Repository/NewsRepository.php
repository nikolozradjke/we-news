<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function listPaginated(int $page = 1, int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('n.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithCategories(int $id): ?News
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.categories', 'c')
            ->addSelect('c')
            ->where('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
