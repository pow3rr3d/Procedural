<?php

namespace App\Repository;

use App\Entity\CompanyProcessStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyProcessStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyProcessStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyProcessStep[]    findAll()
 * @method CompanyProcessStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyProcessStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyProcessStep::class);
    }

    // /**
    //  * @return CompanyProcessStep[] Returns an array of CompanyProcessStep objects
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
    public function findOneBySomeField($value): ?CompanyProcessStep
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
