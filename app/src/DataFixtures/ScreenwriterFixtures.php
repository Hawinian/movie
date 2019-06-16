<?php
/**
 * Screenwriter fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Screenwriter;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ActorFixtures.
 */
class ScreenwriterFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'screenwriter', function () {
            $screenwriter = new Screenwriter();
            $screenwriter->setName($this->faker->name);

            return $screenwriter;
        });

        $manager->flush();
    }
}
