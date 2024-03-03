<?php

namespace App\Repository;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 *
 * @method Like|null find($id, $lockMode = null, $lockVersion = null)
 * @method Like|null findOneBy(array $criteria, array $orderBy = null)
 * @method Like[]    findAll()
 * @method Like[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }



public function countLikesByPublication($publicationId)
{
    return $this->createQueryBuilder('l')
        ->select('count(l.id)')
        ->where('l.publication = :publicationId')
        ->andWhere('l.type = true') // true pour like
        ->setParameter('publicationId', $publicationId)
        ->getQuery()
        ->getSingleScalarResult();
}

public function countDislikesByPublication($publicationId)
{
    return $this->createQueryBuilder('l')
        ->select('count(l.id)')
        ->where('l.publication = :publicationId')
        ->andWhere('l.type = false') // false pour dislike
        ->setParameter('publicationId', $publicationId)
        ->getQuery()
        ->getSingleScalarResult();
}





//    /**
//     * @return Like[] Returns an array of Like objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Like
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
