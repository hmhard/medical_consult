<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 *
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function add(Patient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Patient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Patient[] Returns an array of Patient objects
     */
    public function getData($filter = []): array
    {
        $qb = $this->createQueryBuilder('p');
        if (isset($filter['search']) && $filter['search'] != "") {

            $names = explode(" ", $filter['search']);
            if (sizeof($names) == 3) {

                $qb->andWhere('p.firstName = :fname')
                    ->setParameter('fname', $names[0])

                    ->andWhere('p.middleName = :mname')
                    ->setParameter('mname', $names[1])
                    ->andWhere('p.lastName = :lname')
                    ->setParameter('lname', $names[2]);
            } else if (sizeof($names) == 2) {


                $qb->andWhere('p.firstName = :fname')
                    ->setParameter('fname', $names[0])

                    ->andWhere('p.middleName = :mname')
                    ->setParameter('mname', $names[1]);
            } else if (sizeof($names) == 1 && $names[0]) {


                $qb->andWhere("p.firstName LIKE '" . $names[0] . "%' or p.middleName LIKE '" . $names[0] . "%' or p.lastName LIKE '%" . $names[0] . "%'");
            }
        }


        return $qb->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Patient
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
