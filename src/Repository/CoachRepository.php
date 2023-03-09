<?php

namespace App\Repository;

use App\Entity\Coach;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coach>
 *
 * @method Coach|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coach|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coach[]    findAll()
 * @method Coach[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoachRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coach::class);
    }

    public function save(Coach $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Coach $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Coach[] Returns an array of Coach objects
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

public function findOneBySearchTerm($searchTerm): ?Coach
{
    return $this->createQueryBuilder('c')
    ->where('c.Nom = :searchTerm')
    ->orWhere('c.Prenom = :searchTerm')
    ->setParameter('searchTerm', $searchTerm)
    ->getQuery()
    ->getOneOrNullResult();
}
public function search($query)
{
    $qb = $this->createQueryBuilder('e')
        ->where('e.Nom LIKE :query')
        ->orWhere('e.Prenom LIKE :query')
        ->setParameter('query', '%'.$query.'%')
        ->orderBy('e.Nom', 'ASC')
        ->setMaxResults(10);

    return $qb->getQuery()->getResult();
}

public function findAllByCategory(int $id): array
{
    return $this->createQueryBuilder('c')
        ->join('c.categorie', 'cat')
        ->andWhere('cat.id = :id')
        ->setParameter('id', $id)
        ->orderBy('c.id', 'ASC')
        ->getQuery()
        ->getResult();

}
}
//public function getcoachByCategory($id)  {
 //   $qb= $this->createQueryBuilder('s')
   //     ->join('s.categorie','c')
   //     ->addSelect('c')
   //     ->where('c.id=:id')
    //    ->setParameter('id',$id);
   // return $qb->getQuery()
     //   ->getResult();
// }

