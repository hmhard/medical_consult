<?php

namespace App\Repository;

use App\Entity\ConsultationConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConsultationConversation>
 *
 * @method ConsultationConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultationConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultationConversation[]    findAll()
 * @method ConsultationConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultationConversation::class);
    }

    public function add(ConsultationConversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConsultationConversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ConsultationConversation[] Returns an array of ConsultationConversation objects
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

//    public function findOneBySomeField($value): ?ConsultationConversation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
