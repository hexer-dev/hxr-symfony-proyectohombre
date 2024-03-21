<?php

namespace App\Repository;

use App\Entity\Headquarter;
use App\Entity\Person;
use App\Entity\PersonInProgram;
use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 *
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function add(Person $person)
    {
        $this->getEntityManager()->persist($person);
        $this->getEntityManager()->flush();
    }

    public function remove(Person $person)
    {
        $this->getEntityManager()->remove($person);
        $this->getEntityManager()->flush();
    }

    public function findByNifAndHeadquarter(string $nif, Headquarter $headquarter)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->like('p.nif', ':nif'),
                    $qb->expr()->eq('p.headquarter', ':headquarter')
                )
            )
            ->setParameter('nif', $nif)
            ->setParameter('headquarter', $headquarter)
            ->getQuery()
            ->getResult();
    }

    public function personInHeadquarterWithoutProgram(Headquarter $headquarter, Program $program)
    {
        $qb = $this->createQueryBuilder('p');
        $peopleInProgram = $qb
            ->select('DISTINCT p.id')
            ->join('p.programs', 'pp')
            ->andWhere(
                $qb->expr()->eq('p.headquarter', ':headquarter'),
                $qb->expr()->eq('pp.program', ':program')
            )
            ->setParameter('headquarter', $headquarter)
            ->setParameter('program', $program)
            ->getQuery()
            ->getSingleColumnResult();

        $qb = $this->createQueryBuilder('p');
        $qb
            ->andWhere(
                $qb->expr()->eq('p.headquarter', ':headquarter'),
                $qb->expr()->notIn('p.id', ':peopleInProgram')
            )
            ->setParameter('headquarter', $headquarter)
            ->setParameter('peopleInProgram', $peopleInProgram);

        return $qb;
    }

    //    /**
    //     * @return Person[] Returns an array of Person objects
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

    //    public function findOneBySomeField($value): ?Person
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
