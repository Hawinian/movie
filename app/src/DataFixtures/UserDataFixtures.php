<?php
/**
 * \App\Entity\UserData() fixtures.
 */

namespace App\DataFixtures;

use App\Entity\UserData;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ActorFixtures.
 */
class UserDataFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(15, 'userdata', function ($i) {
            $userData = new UserData();
            $userData->setFirstName($this->faker->firstName);
            $userData->setCity($this->faker->city);
            $userData->setAge($this->faker->numberBetween($min = 20, $max = 90));
            $userData->setUserData($this->getReference('user_'.$i));

            return $userData;
        });

        $this->createMany(4, 'userdata-admin', function ($i) {
            $userData = new UserData();
            $userData->setFirstName($this->faker->firstName);
            $userData->setCity($this->faker->city);
            $userData->setAge($this->faker->numberBetween($min = 20, $max = 90));
            $userData->setUserData($this->getReference('admin_'.$i));

            return $userData;
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
        return [UserFixtures::class];
    }
}
