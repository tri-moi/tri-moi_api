<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<History>
 *
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function save(History $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(History $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function paginateHistory(int $page, int $limit, int $user): array
    {
        $offset = ($page - 1) * $limit;

        $query = $this->createQueryBuilder('h')
            ->andWhere('h.id_user = :user')
            ->setParameter('user', $user)
            ->orderBy('h.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    public function countUserProducts(int $user)
    {
        try {
            $menageres =$this->createQueryBuilder('h')
                ->select('COUNT(h.id)')
                ->andWhere('h.id_type = 9')
                ->andWhere('h.id_user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();
            $verre =$this->createQueryBuilder('h')
                ->select('COUNT(h.id)')
                ->andWhere('h.id_type = 10')
                ->andWhere('h.id_user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();
            $recyclables =$this->createQueryBuilder('h')
                ->select('COUNT(h.id)')
                ->andWhere('h.id_type = 11')
                ->andWhere('h.id_user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();
            $textile =$this->createQueryBuilder('h')
                ->select('COUNT(h.id)')
                ->andWhere('h.id_type = 12')
                ->andWhere('h.id_user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getSingleScalarResult();
            return [
                'menageres' => $menageres,
                'verre' => $verre,
                'recyclables' => $recyclables,
                'textile' => $textile,
                'total' => $menageres+$verre+$recyclables+$textile
            ];
        } catch (NonUniqueResultException $e) {
            return 'error';
        }
    }
//    /**
//     * @return History[] Returns an array of History objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?History
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
