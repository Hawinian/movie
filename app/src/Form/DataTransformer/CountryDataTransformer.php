<?php
/**
 * Actors data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ActorsDataTransformer.
 */
class CountryDataTransformer implements DataTransformerInterface
{
    /**
     * Actor repository.
     *
     * @var CountryRepository|null
     */
    private $repository = null;

    /**
     * ActorsDataTransformer constructor.
     *
     * @param CountryRepository $repository Actor repository
     */
    public function __construct(CountryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of actors to string of names.
     *
     * @param Country $country
     *
     * @return string Result
     */
    public function transform($country): string
    {
        if (null === $country) {
            return '';
        }

        return $country->getName();
    }

    /**
     * @param mixed $value
     *
     * @return Country
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): Country
    {
        $newCountry = new Country();
        if ('' !== trim($value)) {
            $country = $this->repository->findOneByName(strtolower($value));
            if (null == $country) {
                $newCountry->setName($value);
                $this->repository->save($newCountry);

                return $newCountry;
            }
        }

        return $country;
    }
}
