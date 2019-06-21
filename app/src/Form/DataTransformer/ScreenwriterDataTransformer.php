<?php
/**
 * Screenwriter data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Screenwriter;
use App\Repository\ScreenwriterRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ActorsDataTransformer.
 */
class ScreenwriterDataTransformer implements DataTransformerInterface
{
    /**
     * Actor repository.
     *
     * @var ScreenwriterRepository|null
     */
    private $repository = null;

    /**
     * ActorsDataTransformer constructor.
     *
     * @param ScreenwriterRepository $repository Actor repository
     */
    public function __construct(ScreenwriterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of actors to string of names.
     *
     * @param Screenwriter $screenwriter
     *
     * @return string Result
     */
    public function transform($screenwriter): string
    {
        if (null === $screenwriter) {
            return '';
        }

        return $screenwriter->getName();
    }

    /**
     * @param mixed $value
     *
     * @return Screenwriter
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function reverseTransform($value): Screenwriter
    {
        $newScreenwriter = new Screenwriter();
        if ('' !== trim($value)) {
            $screenwriter = $this->repository->findOneByName(strtolower($value));
            if (null == $screenwriter) {
                $newScreenwriter->setName($value);
                //$this->repository->save($newScreenwriter);
                return $newScreenwriter;
            }
        }

        return $screenwriter;
    }
}
