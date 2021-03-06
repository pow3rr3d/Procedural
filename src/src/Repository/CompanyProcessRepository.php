<?php

namespace App\Repository;

use App\Entity\Company;
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


    public function getAllQuery(CompanyProcess $search): \Doctrine\ORM\Query
    {
        $qb = $this->createQueryBuilder('s');


        if ($search->getCompany() !== null) {
            $qb
                ->orWhere($qb->expr()->eq('s.company', ':company'))
                ->setParameter('company', $search->getCompany());

        }
        if ($search->getProcess() !== null) {
            $qb
                ->orWhere($qb->expr()->eq('s.process', ':process'))
                ->setParameter('process', $search->getProcess());
        }
        if ($search->getState() !== null) {
            $qb
                ->orWhere($qb->expr()->eq('s.state' , ':state'))
                ->setParameter('state', $search->getState());
        }

        return $qb->getQuery();
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
