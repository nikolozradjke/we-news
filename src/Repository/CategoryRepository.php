<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
        $this->conn = $this->getEntityManager()->getConnection();
    }

    public function listPaginated(int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    /**
     * Fetch all categories with their latest N news, assigning each news to only one category
     * to avoid duplicates when a news belongs to multiple categories.
     * 
     * This method uses native SQL with window functions (ROW_NUMBER()) and Common Table Expressions (CTEs).
     *
     * Reason for using native SQL instead of Doctrine ORM:
     * - Doctrine ORM does not natively support SQL window functions or CTEs, which are essential
     *   here for efficiently selecting the latest N news per category and avoiding duplicates.
     * - Implementing this logic purely with Doctrine QueryBuilder would require multiple queries
     *   or complex post-processing in PHP, resulting in performance overhead and more code complexity.
     * - Native SQL executes this complex query in a single round-trip to the database, 
     *   leveraging MySQL 8.0+ advanced features for better performance and cleaner code.
     * - This approach scales better for large datasets and reduces memory consumption 
     *   compared to fetching large collections and filtering in PHP.
     *
     * Note: This requires MySQL 8.0 or higher due to the usage of window functions and CTEs.
    */
    public function findCategoriesWithLatestNews(int $limit = 3): array
    {
        $sql = <<<SQL
            WITH news_single_category AS (
                SELECT 
                    n.id AS news_id,
                    n.title AS news_title,
                    n.short_description,
                    n.picture,
                    n.created_at,
                    nc.category_id,
                    ROW_NUMBER() OVER (PARTITION BY n.id ORDER BY nc.category_id ASC) AS rn_cat
                FROM news n
                JOIN news_category nc ON n.id = nc.news_id
            ),
            ranked_news AS (
                SELECT 
                    c.id AS category_id,
                    c.title AS category_title,
                    nsc.news_id,
                    nsc.news_title,
                    nsc.short_description,
                    nsc.picture,
                    nsc.created_at,
                    ROW_NUMBER() OVER (PARTITION BY c.id ORDER BY nsc.created_at DESC) AS rn_news
                FROM category c
                JOIN news_single_category nsc ON c.id = nsc.category_id
                WHERE nsc.rn_cat = 1
            )
            SELECT * FROM ranked_news
            WHERE rn_news <= :limit
            ORDER BY category_id, created_at DESC;
            SQL;

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue('limit', $limit);
            $result = $stmt->executeQuery()->fetchAllAssociative();

            $grouped = [];
            foreach ($result as $row) {
                $grouped[$row['category_id']][] = $row;
            }

            $categories = [];
            foreach ($grouped as $rows) {
                $first = $rows[0];
                $categories[] = [
                    'id' => $first['category_id'],
                    'title' => $first['category_title'],
                    'latest_news' => array_map(fn($r) => [
                        'id' => $r['news_id'],
                        'title' => $r['news_title'],
                        'short_description' => $r['short_description'],
                        'picture' => $r['picture'],
                        'created_at' => $r['created_at'],
                    ], $rows),
                ];
            }

        return $categories;
    }

}
