<?php

namespace App\Repository;

use App\Entity\Consultation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Consultation>
 *
 * @method Consultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultation[]    findAll()
 * @method Consultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }

//    /**
//     * @return Consultation[] Returns an array of Consultation objects
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

//    public function findOneBySomeField($value): ?Consultation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findByPatientId(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idp = :patientId')
            ->setParameter('patientId', 5)
            ->getQuery()
            ->getResult();
    }

public function findByTherapistId(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idt = :therapistId')
            ->setParameter('therapistId', 5) // Change 5 to the desired therapist ID
            ->getQuery()
            ->getResult();
    }

public function findConsultationsBetweenDates(DateTime $startDate, DateTime $endDate, string $pathologie): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date_c BETWEEN :start_date AND :end_date')
            ->andWhere('c.pathologie = :pathologie')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->setParameter('pathologie', $pathologie)
            ->getQuery()
            ->getResult();
    }
    


    public function findAllOrderedByDate(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date_c', 'ASC') // Order by dateC in ascending order
            ->getQuery()
            ->getResult();
    }
}
