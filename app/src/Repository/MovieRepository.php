<?php

/** Movie Repository */

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    /**
     * MovieRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('m.country', 'c')
            ->join('m.director', 'd')
            ->orderBy('m.title', 'ASC');
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
     * Query movies by author.
     *
     * @param User|null $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(User $user = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($user)) {
            $queryBuilder->andWhere('m.author = :author')
                ->setParameter('author', $user);
        }

        return $queryBuilder;
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
        return $queryBuilder ?: $this->createQueryBuilder('m');
    }

    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
