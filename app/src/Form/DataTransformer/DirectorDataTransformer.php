<?php
/**
 * Director data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Director;
use App\Repository\DirectorRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ActorsDataTransformer.
 */
class DirectorDataTransformer implements DataTransformerInterface
{
    /**
     * Actor repository.
     *
     * @var DirectorRepository|null
     */
    private $repository = null;

    /**
     * DirectorDataTransformer constructor.
     *
     * @param DirectorRepository $repository Director repository
     */
    public function __construct(DirectorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Director $director
     *
     * @return string Result
     */
    public function transform($director): string
    {
        if (null === $director) {
            return '';
        }

        return $director->getName();
    }

    /**
     * @param mixed $value
     *
     * @return Director
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): Director
    {
        $newDirector = new Director();
        if ('' !== trim($value)) {
            $director = $this->repository->findOneByName(strtolower($value));
            if (null == $director) {
                $newDirector->setName($value);
                //$this->repository->save($newDirector);
                return $newDirector;
            }
        }

        return $director;
    }
}
