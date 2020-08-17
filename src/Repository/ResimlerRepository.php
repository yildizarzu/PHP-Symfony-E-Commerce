<?php

namespace App\Repository;

use App\Entity\Resimler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Resimler|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resimler|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resimler[]    findAll()
 * @method Resimler[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResimlerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Resimler::class);
    }

    // /**
    //  * @return Resimler[] Returns an array of Resimler objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Resimler
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
