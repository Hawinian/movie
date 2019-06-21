<?php
/**
 * Actors data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ActorsDataTransformer.
 */
class ActorsDataTransformer implements DataTransformerInterface
{
    /**
     * Actor repository.
     *
     * @var ActorRepository|null
     */
    private $repository = null;

    /**
     * ActorsDataTransformer constructor.
     *
     * @param ActorRepository $repository Actor repository
     */
    public function __construct(ActorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of actors to string of names.
     *
     * @param Collection $actors Actors entity collection
     *
     * @return string Result
     */
    public function transform($actors): string
    {
        if (null == $actors) {
            return '';
        }

        $actorNames = [];

        foreach ($actors as $actor) {
            $actorNames[] = $actor->getName();
        }

        return implode(',', $actorNames);
    }

    /**
     * Transform string of actor names into array of Actor entities.
     *
     * @param string $value String of actor names
     *
     * @return array Result
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $actorTitles = explode(',', $value);
        $actors = [];
        foreach ($actorTitles as $actorTitle) {
            if ('' !== trim($actorTitle)) {
                $actor = $this->repository->findOneByName(strtolower($actorTitle));
                if (null == $actor) {
                    $actor = new Actor();
                    $actor->setName($actorTitle);
                    // $this->repository->save($actor);
                }
                $actors[] = $actor;
            }
        }

        return $actors;
    }
}
