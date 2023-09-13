<?php

namespace App\Repository;

use App\Entity\ETH;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ETH>
 *
 * @method ETH|null find($id, $lockMode = null, $lockVersion = null)
 * @method ETH|null findOneBy(array $criteria, array $orderBy = null)
 * @method ETH[]    findAll()
 * @method ETH[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ETHRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ETH::class);
    }

//    /**
//     * @return ETH[] Returns an array of ETH objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ETH
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
