<?php

namespace App\Repository;

use App\Entity\Shopcart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Shopcart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shopcart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shopcart[]    findAll()
 * @method Shopcart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopcartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Shopcart::class);
    }

    public function toplam($id):float{

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT sum(p.fiyat * s.quantity) as total
        FROM App\Entity\Shopcart s, App\Entity\Product p WHERE s.productid = p.id and s.userid=:id')->setParameter('id', $id);
        $result =  $query->getResult();

        if($result[0]["total"]){

            return $result[0]["total"];

        }
        else{
            return 0;
        }

    }

    public function urunler($id):array{

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT p.urunadi,p.fiyat,  s.id,s.quantity,s.productid,s.userid, (p.fiyat * s.quantity) as total
        FROM App\Entity\Shopcart s, App\Entity\Product p
        WHERE s.productid = p.id and s.userid =:id')->setParameter('id', $id);
        return  $query->getResult();

    }

    // /**
    //  * @return Shopcart[] Returns an array of Shopcart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shopcart
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
