<?php
/**
 * Country fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ActorFixtures.
 */
class CountryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'country', function () {
            $country = new Country();
            $country->setName($this->faker->country);

            return $country;
        });

        $manager->flush();
    }
}
