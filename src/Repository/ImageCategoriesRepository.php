<?php

namespace App\Repository;

use App\Entity\ImageCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageCategories[]    findAll()
 * @method ImageCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageCategories::class);
    }

    // /**
    //  * @return ImageCategories[] Returns an array of ImageCategories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageCategories
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
