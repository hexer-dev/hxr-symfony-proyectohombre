<?php

namespace App\Repository;

use App\Entity\Headquarter;
use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Program>
 *
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function add(Program $program)
    {
        $this->getEntityManager()->persist($program);
        $this->getEntityManager()->flush();
    }

    public function remove(Program $program)
    {
        $this->getEntityManager()->remove($program);
        $this->getEntityManager()->flush();
    }

    public function allProgramsActived()
    {
        $qb = $this->createQueryBuilder('p');

        $now = new \DateTime('NOW');

        return $qb
            ->andWhere(
                $qb->expr()->gte('p.date_end', ':now')
            )
            ->setParameter('now', $now->format('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function managerPrograms(Headquarter $headquarter): array
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('p.headquarter', ':headquarter'),
                    $qb->expr()->gte('p.date_end', ':dateEnd')
                )
            )
            ->setParameter('headquarter', $headquarter)
            ->setParameter('dateEnd', new \DateTime('NOW'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function managerProgramsQuery(Headquarter $headquarter)
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('p.headquarter', ':headquarter'),
                    $qb->expr()->gte('p.date_end', ':dateEnd')
                )
            )
            ->setParameter('headquarter', $headquarter)
            ->setParameter('dateEnd', new \DateTime('NOW'))
        ;

        return $qb;
    }

    public function allProgramsFinished()
    {
        $qb = $this->createQueryBuilder('p');

        $now = new \DateTime('NOW');

        return $qb
            ->andWhere(
                $qb->expr()->lte('p.date_end', ':now')
            )
            ->setParameter('now', $now->format('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function managerProgramsFinished(Headquarter $headquarter): array
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('p.headquarter', ':headquarter'),
                    $qb->expr()->lte('p.date_end', ':dateEnd')
                )
            )
            ->setParameter('headquarter', $headquarter)
            ->setParameter('dateEnd', new \DateTime('NOW'))
            ->getQuery()
            ->getResult()
        ;
    }

    

//    /**
//     * @return Program[] Returns an array of Program objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Program
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
