<?php

namespace App\Repository;

use App\Entity\Drug;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Drug>
 *
 * @method Drug|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drug|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drug[]    findAll()
 * @method Drug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drug::class);
    }

    public function add(Drug $drug)
    {
        $this->getEntityManager()->persist($drug);
        $this->getEntityManager()->flush();
    }

    public function remove(Drug $drug)
    {
        $this->getEntityManager()->remove($drug);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return Drug[] Returns an array of Drug objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Drug
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
