<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Clocking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clocking>
 *
 * @method Clocking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clocking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clocking[]    findAll()
 * @method Clocking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClockingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clocking::class);
    }
    public function findExistingClocking(\DateTimeInterface $date, int $userId): ?Clocking
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date = :date')
            ->andWhere('c.clockingUser = :user')
            ->setParameter('date', $date)
            ->setParameter('user', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Clocking[] Returns an array of Clocking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Clocking
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
