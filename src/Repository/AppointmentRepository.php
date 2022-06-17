<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    private $tokenInterface;
    public function __construct(ManagerRegistry $registry,Security $tokenInterface)
    {
        parent::__construct($registry, Appointment::class);
        $this->tokenInterface=$tokenInterface;
    }

    public function clearOld(): void
    {
      
        foreach ($this->getOldData() as $key => $value) {
          $value->setIsMade(true);

        }
      
            $this->getEntityManager()->flush();
      
     }
    public function add(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getCount($filter = [])
    {
        $qb = $this->createQueryBuilder('a');
        if (isset($filter['active']))
            $qb->andWhere('a.appointmentDate > :date')
                ->setParameter('date', new \DateTime('now'));

        if (isset($filter['doctor']))
            $qb->andWhere('a.doctor = :doctor')
                ->setParameter('doctor', $filter['doctor']);


        return $qb->select('count(a.id)')->getQuery()
            ->getSingleScalarResult();
    }
    public function getData($filter = []): array
    {
          /** 
         * @var \App\Entity\User|null $patient
         */

        $patient=$this->tokenInterface->getUser();
    
        $qb=  $this->createQueryBuilder('a');
        if($patient->getUserType()->getId()!=1)
     {
        if($patient->getUserType()->getId()==2){

            
            $qb  =$qb->andWhere("a.doctor= :doctor")
            ->setParameter('doctor',$patient->getDoctor());
           
        }
        
         else{
          
             $qb  =$qb->andWhere("a.patient= :patient")
             ->setParameter('patient',$patient->getPatient());
 
         }
        }

          
         
        return $qb
            ->orderBy('a.appointmentDate', 'ASC')
            ->addOrderBy('a.isMade', 'DESC')

            ->getQuery()
            ->getResult();
    }
    public function hasActiveAppointment(Doctor $doctor,$date)
    {
        
        $qb=  $this->createQueryBuilder('a');
       
          
         $qb=$qb
            ->andWhere('a.doctor = :doctor')->setParameter('doctor',$doctor)
            ->andWhere('a.appointmentDate <= :date')->setParameter('date',(new \DateTime($date))->format("Y-m-d 23:59:59"))
            ->andWhere('a.appointmentDate >= :date1')->setParameter('date1',(new \DateTime($date))->format("Y-m-d 00:00:00"))
           

            ->getQuery()
            ->getResult()
            ;
            return sizeof($qb)>0;
    }
    public function getOldData($filter = []): array
    {
        
        $qb=  $this->createQueryBuilder('a');
       
          
        return $qb
            ->andWhere('a.appointmentDate < :date')->setParameter('date',(new \DateTime))
           

            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Appointment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
