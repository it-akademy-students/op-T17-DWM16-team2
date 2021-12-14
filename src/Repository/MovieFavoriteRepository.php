<?php

namespace App\Repository;

use App\Entity\MovieFavorite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieFavorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieFavorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieFavorite[]    findAll()
 * @method MovieFavorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieFavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieFavorite::class);
    }

    // /**
    //  * @return MovieFavorite[] Returns an array of MovieFavorite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovieFavorite
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
