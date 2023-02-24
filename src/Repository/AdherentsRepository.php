<?php

namespace App\Repository;

use App\Entity\Adherents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adherents>
 *
 * @method Adherents|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherents|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherents[]    findAll()
 * @method Adherents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherents::class);
    }

    public function save(Adherents $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Adherents $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCourse($value): array 
    {
        return $this ->createQueryBuilder('a')
        ->leftJoin('a.user', 'u')
        ->where('a.cours = :cours')
        ->setParameter('cours', $value)
        ->getQuery()
        ->getResult();
    }
    // find adherent where adherent id and course id
    public function findAdherentByCourseId($adherentId, $courseId)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :adherentId')
            ->andWhere('a.cours = :courseId')
            ->setParameter('adherentId', $adherentId)
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Adherents[] Returns an array of Adherents objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Adherents
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
