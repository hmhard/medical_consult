<?php

namespace App\Repository;

use App\Entity\ConsultationRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConsultationRequest>
 *
 * @method ConsultationRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultationRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultationRequest[]    findAll()
 * @method ConsultationRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultationRequest::class);
    }

    public function add(ConsultationRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConsultationRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ConsultationRequest[] Returns an array of ConsultationRequest objects
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

//    public function findOneBySomeField($value): ?ConsultationRequest
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
