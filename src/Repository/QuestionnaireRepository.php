<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Questionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Questionnaire>
 *
 * @method Questionnaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questionnaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questionnaire[]    findAll()
 * @method Questionnaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionnaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questionnaire::class);
    }

    public function countByUser($questionnaireId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(DISTINCT ans.ID_User)')
        ->from(Answer::class, 'ans')
        ->innerJoin('ans.question', 'q')
        ->where('q.questionnaire = :questionnaireId')
        ->setParameter('questionnaireId', $questionnaireId);

        return $qb->getQuery()->getSingleScalarResult();
    }
    public function findAllQuestionnairesWithUserCount()
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q.id AS questionnaireId, COUNT(DISTINCT a.ID_User) AS userCount')
            ->leftJoin('q.questions', 'qs')
            ->leftJoin('qs.answers', 'a')
            ->groupBy('q.id');

        return $qb->getQuery()->getResult();
    }


//    /**
//     * @return Questionnaire[] Returns an array of Questionnaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Questionnaire
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
