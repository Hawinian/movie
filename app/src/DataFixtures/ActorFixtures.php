<?php
/**
 * Actor fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ActorFixtures.
 */
class ActorFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'actor', function () {
            $actor = new Actor();
            $actor->setName($this->faker->name);
            $limit = mt_rand(5, 10);
            for ($i = 0; $i < $limit; ++$i) {
                $actor->addMovie($this->getRandomReference('movie'));
            }

            return $actor;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [MovieFixtures::class];
    }
}
