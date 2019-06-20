<?php

/** Screenwriter Repository */

namespace App\Repository;

use App\Entity\Screenwriter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Screenwriter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Screenwriter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Screenwriter[]    findAll()
 * @method Screenwriter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScreenwriterRepository extends ServiceEntityRepository
{
    /**
     * ScreenwriterRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Screenwriter::class);
    }

    /**
     * Save record.
     *
     * @param Screenwriter $screenwriter
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Screenwriter $screenwriter): void
    {
        $this->_em->persist($screenwriter);
        $this->_em->flush($screenwriter);
    }

    /**
     * Delete record.
     *
     * @param Screenwriter $screenwriter
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Screenwriter $screenwriter): void
    {
        $this->_em->remove($screenwriter);
        $this->_em->flush($screenwriter);
    }

    // /**
    //  * @return Screenwriter[] Returns an array of Screenwriter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Screenwriter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
