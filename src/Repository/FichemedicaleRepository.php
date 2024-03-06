<?php

namespace App\Repository;
use DateTime;
use App\Entity\Fichemedicale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fichemedicale>
 *
 * @method Fichemedicale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichemedicale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichemedicale[]    findAll()
 * @method Fichemedicale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichemedicaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichemedicale::class);
    }

//    /**
//     * @return Fichemedicale[] Returns an array of Fichemedicale objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fichemedicale
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findAllExcept0(): array
{
    return $this->createQueryBuilder('f')
        ->andWhere('f.id != :id')
        ->setParameter('id', 0)
        ->getQuery()
        ->getResult();
}


public function findAllFicheMedicaleOrderedByDateCreation(): array
{
    return $this->createQueryBuilder('f')
        ->where('f.id != :id')
        ->setParameter('id', 0)
        ->orderBy('f.date_creation', 'ASC') 
        ->getQuery()
        ->getResult();
}

public function findFichesBetweenDates(DateTime $startDate, DateTime $endDate,DateTime $startDate1, DateTime $endDate1): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.date_creation BETWEEN :start_date AND :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->andWhere('f.derniere_maj BETWEEN :start_date1 AND :end_date1')
            ->setParameter('start_date1', $startDate1)
            ->setParameter('end_date1', $endDate1)
            ->getQuery()
            ->getResult();
    }
 

    public function findByTherapistId($id): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id_t = :therapistId')
            ->setParameter('therapistId',$id) 
            ->getQuery()
            ->getResult();
    }
    public function findByTherapistAndPatientId($therapistId, $patientId)
{
    try {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id_t = :therapistId')
            ->andWhere('f.id_p = :patientId')
            ->setParameter('therapistId', $therapistId)
            ->setParameter('patientId', $patientId)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    } catch (NoResultException $e) {
        return null;
    }
}
}

