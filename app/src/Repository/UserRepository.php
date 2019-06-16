<?php

/** User Repository */

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('u.userdata', 'us')
            ->orderBy('u.email', 'ASC');
    }

    /**
     * Query movies by author.
     *
     * @param User|null $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByNotAdmin(): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('u.roles != :admin_role')
                ->setParameter('admin_role', 'a:2:{i:0;s:9:"ROLE_USER";i:1;s:10:"ROLE_ADMIN";}');

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param Movie $movie
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Movie $movie): void
    {
        $this->_em->persist($movie);
        $this->_em->flush($movie);
    }

    /**
     * Save record.
     *
     * @param User $user
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveUser(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush($user);
    }

    /**
     * Delete record.
     *
     * @param Movie $movie
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Movie $movie): void
    {
        $this->_em->remove($movie);
        $this->_em->flush($movie);
    }

    /**
     * Delete record.
     *
     * @param User $user
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteUser(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush($user);
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('u');
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
