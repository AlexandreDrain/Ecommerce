<?php

namespace App\Repository;

use App\Entity\ProductReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductReview[]    findAll()
 * @method ProductReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductReview::class);
    }

    // /**
    //  * @return ProductReview[] Returns an array of ProductReview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductReview
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
