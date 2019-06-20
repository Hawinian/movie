<?php
/**
 * Review fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ReviewFixtures.
 */
class ReviewFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(300, 'review', function () {
            $review = new Review();
            $review->setContent($this->faker->sentence);
            $review->setDate($this->faker->dateTimeThisYear);
            $review->setRate($this->faker->numberBetween($min = 1, $max = 10));
            $review->setAuthor($this->getRandomReference('user'));
            $review->setMovie($this->getRandomReference('movie'));

            return $review;
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
        return [UserFixtures::class, MovieFixtures::class];
    }
}
