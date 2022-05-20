<?php

namespace App\Repository;

use App\Entity\CaseCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CaseCategory>
 *
 * @method CaseCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CaseCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CaseCategory[]    findAll()
 * @method CaseCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaseCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CaseCategory::class);
    }

    public function add(CaseCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CaseCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CaseCategory[] Returns an array of CaseCategory objects
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

//    public function findOneBySomeField($value): ?CaseCategory
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
