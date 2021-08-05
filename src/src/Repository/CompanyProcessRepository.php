<?php

namespace App\Repository;

use App\Entity\CompanyProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyProcess[]    findAll()
 * @method CompanyProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyProcess::class);
    }

    // /**
    //  * @return CompanyProcess[] Returns an array of CompanyProcess objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompanyProcess
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
