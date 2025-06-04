<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function listPaginated(Category $category = null)
    {
        $qb = $this->createQueryBuilder('n')
            ->orderBy('n.id', 'DESC');
            
        if ($category){
            $qb->join('n.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $category->getId());
        }

        return $qb->getQuery()->getResult();
    }

    public function getTopMostCommentedNews(): array
    {
        return $this->createQueryBuilder('n')
            ->select('n', 'COUNT(c.id) as commentCount')
            ->leftJoin('n.comments', 'c')
            ->groupBy('n.id')
            ->orderBy('commentCount', 'DESC')
            ->addOrderBy('n.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    
}
