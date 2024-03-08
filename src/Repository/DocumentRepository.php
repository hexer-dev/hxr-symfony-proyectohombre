<?php

namespace App\Repository;

use App\Entity\Document;
use App\Entity\Headquarter;
use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function add(Document $document)
    {
        $this->getEntityManager()->persist($document);
        $this->getEntityManager()->flush();
    }

    public function remove(Document $document)
    {
        $this->getEntityManager()->remove($document);
        $this->getEntityManager()->flush();
    }

    public function headquarterPrivateDocument(): array
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->isNull('d.headquarter'),
                    $qb->expr()->isNull('d.program')
                )
            )
            ->getQuery()
            ->getResult()
        ;
    }

    public function managerPrivateDocument(Headquarter $headquarter): array
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('d.headquarter', ':headquarter'),
                    $qb->expr()->isNull('d.program')
                )
            )
            ->setParameter('headquarter', $headquarter)
            ->getQuery()
            ->getResult()
        ;
    }

    public function managerProgramDocument(Headquarter $headquarter, Program $program): array
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('d.headquarter', ':headquarter'),
                    $qb->expr()->eq('d.program', ':program')
                )
            )
            ->setParameter('headquarter', $headquarter)
            ->setParameter('program', $program)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Document[] Returns an array of Document objects
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

    //    public function findOneBySomeField($value): ?Document
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
