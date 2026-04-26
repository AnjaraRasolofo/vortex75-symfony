<?php

namespace App\Repository;

use App\Entity\Tutorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tutorial>
 */
class TutorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tutorial::class);
    }

    public function paginate(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC');

        $data = (clone $qb)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $total = (clone $qb)
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'data' => $data,
            'total' => $total,
        ];
    }

    public function paginateByCategory(?string $slug, int $page, int $limit): array
    {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC');

        if ($slug) {
            $qb->join('t.category', 'c')
            ->andWhere('c.slug = :slug')
            ->setParameter('slug', $slug);
        }

        $offset = ($page - 1) * $limit;

        $data = (clone $qb)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $total = (clone $qb)
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return ['data' => $data, 'total' => $total];
    }

    //    /**
    //     * @return Tutorial[] Returns an array of Tutorial objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tutorial
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
