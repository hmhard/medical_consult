<?php

namespace App\Repository;

use App\Entity\ConsultationRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

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
    private $tokenInterface;
    public function __construct(ManagerRegistry $registry,Security $tokenInterface)
    {
        parent::__construct($registry, ConsultationRequest::class);
        $this->tokenInterface = $tokenInterface;
    }



    public function getData()
    {
        $patient=$this->tokenInterface->getUser();
     
      $qb=  $this->createQueryBuilder('c');
      if(!in_array($patient?->getUserType()->getId(),[1,2]))
     $qb   ->andWhere("c.patient= :patient")
        ->setParameter('patient',$patient?->getPatient());

        return $qb->orderBy('c.id', 'DESC')->getQuery()->getResult();

    }
    public function getPaymentList()
    {
        $patient=$this->tokenInterface->getUser();
     
      $qb=  $this->createQueryBuilder('c');
      if(!in_array($patient?->getUserType()->getId(),[1,2]))
     $qb   ->andWhere("c.patient= :patient")
        ->setParameter('patient',$patient?->getPatient());

        return $qb->orderBy('c.id', 'DESC')->getQuery()->getResult();

    }
    public function getTotalPrice($today=false)
    {
      
      $qb=  $this->createQueryBuilder('c')
      ->join('c.caseCategory','cc')
      ->join('cc.paymentFee','pf')
      ;

      $qb   ->select('sum(pf.price*pf.taxRate)')
      ->andWhere("c.status=1")
        ;

        return $qb->getQuery()->getSingleScalarResult();

    }
    public function hasActiveRequest()
    {
        $patient=$this->tokenInterface->getUser();
     
      $qb=  $this->createQueryBuilder('c');
      $qb=$qb   
      ->andWhere("c.patient= :patient")
      ->setParameter('patient',$patient?->getPatient())
      ->andWhere("c.status !=4")
      ;

         $qb=$qb->orderBy('c.id', 'DESC')
        ->getQuery()
        ->getResult();
        
        
        return sizeof($qb)>0;

        

    }
    public function getCount($filter = [])
    {
        $qb = $this->createQueryBuilder('c');
        if (isset($filter['active']))
            $qb->andWhere('c.status = :status')
                ->setParameter('status', 1);

        if (isset($filter['doctor']))
            $qb->andWhere('c.assignedTo = :doctor')
                ->setParameter('doctor', $filter['doctor']);


        return $qb->select('count(c.id)')->getQuery()
            ->getSingleScalarResult();
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
