<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\PersonInProgram;
use App\Entity\Program;
use App\Filter\ReportFiler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonInProgram>
 *
 * @method PersonInProgram|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonInProgram|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonInProgram[]    findAll()
 * @method PersonInProgram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonInProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonInProgram::class);
    }

    public function add(PersonInProgram $personInProgram)
    {
        $this->getEntityManager()->persist($personInProgram);
        $this->getEntityManager()->flush();
    }

    public function remove(PersonInProgram $personInProgram)
    {
        $this->getEntityManager()->remove($personInProgram);
        $this->getEntityManager()->flush();
    }

    public function getDataByFilter(ReportFiler $filter)
    {
        $qb = $this->createQueryBuilder('pp');

        $qb->select('pp, tt, pr')
            ->join('pp.timetables', 'tt')
            ->join('pp.program', 'pr')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->isNull('tt.dateEnd'),
                        $qb->expr()->gte('tt.dateEnd', ':limit_start'),
                    ),
                    $qb->expr()->lte('tt.dateStart', ':limit_end')
                ),
            )
            ->setParameter('limit_start', $filter->getDateStart()->format('Y-m-d'))
            ->setParameter('limit_end', $filter->getDateEnd()->format('Y-m-d'));

        if (null !== $filter->getProgram()) {
            $qb
                ->andWhere(
                    $qb->expr()->eq('pp.program', ':program')
                )
                ->setParameter('program', $filter->getProgram());
        }

        return $qb->getQuery()->getResult();
    }

    public function findByPersonAndProgram(Person $person, Program $program)
    {
        $qb = $this->createQueryBuilder('pp');

        return $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('pp.person', ':person'),
                    $qb->expr()->eq('pp.program', ':program')
                )
            )
            ->setParameter('person', $person)
            ->setParameter('program', $program)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return PersonInProgram[] Returns an array of PersonInProgram objects
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

    //    public function findOneBySomeField($value): ?PersonInProgram
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
