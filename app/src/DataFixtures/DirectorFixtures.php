<?php
/**
 * Director fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Director;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ActorFixtures.
 */
class DirectorFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'director', function () {
            $director = new Director();
            $director->setName($this->faker->name);

            return $director;
        });

        $manager->flush();
    }
}
