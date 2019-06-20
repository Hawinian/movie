<?php

/** Director Repository */

namespace App\Repository;

use App\Entity\Director;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Director|null find($id, $lockMode = null, $lockVersion = null)
 * @method Director|null findOneBy(array $criteria, array $orderBy = null)
 * @method Director[]    findAll()
 * @method Director[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectorRepository extends ServiceEntityRepository
{
    /**
     * DirectorRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Director::class);
    }

    /**
     * Save record.
     *
     * @param Director $director
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Director $director): void
    {
        $this->_em->persist($director);
        $this->_em->flush($director);
    }

    /**
     * Delete record.
     *
     * @param Director $director
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Director $director): void
    {
        $this->_em->remove($director);
        $this->_em->flush($director);
    }

    // /**
    //  * @return Director[] Returns an array of Director objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Director
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
