<?php

namespace App\Repository;

use App\Entity\UserBadge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBadge>
 *
 * @method UserBadge|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBadge|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBadge[]    findAll()
 * @method UserBadge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBadge::class);
    }

    public function save(UserBadge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserBadge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addUserBadge($user, $badge, $level, $nmbreScan): void
    {
        $userBadge = new UserBadge();
        $userBadge->setIdUser($user);
        $userBadge->setBadge(json_encode($badge));
        $userBadge->setLevel(json_encode($level));
        $userBadge->setNmbreScan($nmbreScan);
        $this->save($userBadge);

        $this->getEntityManager()->flush();
    }
//    /**
//     * @return UserBadge[] Returns an array of UserBadge objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserBadge
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
