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
public function findByPatientId($id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idp = :patientId')
            ->setParameter('patientId',$id)
            ->getQuery()
            ->getResult();
    }

public function findByTherapistId($id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idt = :therapistId')
            ->setParameter('therapistId',$id) // Change 5 to the desired therapist ID
            ->getQuery()
            ->getResult();
    }

public function findConsultationsBetweenDatespatient(DateTime $startDate, DateTime $endDate, string $pathologie,$id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date_c BETWEEN :start_date AND :end_date')
            ->andWhere('c.pathologie = :pathologie')
            ->andWhere('c.idp = :idp')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->setParameter('pathologie', $pathologie)
            ->setParameter('idp', $id)
            ->getQuery()
            ->getResult();
    }

    public function findConsultationsBetweenDatestherap(DateTime $startDate, DateTime $endDate, string $pathologie,$id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date_c BETWEEN :start_date AND :end_date')
            ->andWhere('c.pathologie = :pathologie')
            ->andWhere('c.idt = :idt')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->setParameter('pathologie', $pathologie)
            ->setParameter('idt', $id)
            ->getQuery()
            ->getResult();
    }
    
    public function findAllOrderedByDatep($id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idp = :idp')
            ->orderBy('c.date_c', 'ASC') // Order by dateC in ascending order
            ->setParameter('idp', $id)
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedByDatet($id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idt = :idt')
            ->orderBy('c.date_c', 'ASC') // Order by dateC in ascending order
            ->setParameter('idt', $id)
            ->getQuery()
            ->getResult();
    }
    public function findAllOrderedByDateDesc(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date_c', 'DESC') // Order by dateC in ascending order
  //          ->setParameter('idt', $id)
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
    public function countConfirmedConsultations(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.confirmation = :confirmed')
            ->setParameter('confirmed', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUnconfirmedConsultations(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.confirmation = :confirmed')
            ->setParameter('confirmed', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
