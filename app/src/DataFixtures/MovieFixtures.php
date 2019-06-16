<?php
/**
 * Movie fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class MovieFixtures.
 */
class MovieFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'movie', function () {
            $movie = new Movie();
            $movie->setTitle($this->faker->word);
            $movie->setYear($this->faker->numberBetween($min = 1950, $max = 2019));
            $movie->setRate($this->faker->numberBetween($min = 1, $max = 10));
            $movie->setBoxoffice($this->faker->numberBetween($min = 100000, $max = 100000000));
            //$movie->setImage($this->faker->word);
            $movie->setCountry($this->getRandomReference('country'));
            $movie->setCategory($this->getRandomReference('category'));
            $movie->setScreenwriter($this->getRandomReference('screenwriter'));
            $movie->setDirector($this->getRandomReference('director'));
            $movie->setAuthor($this->getRandomReference('user'));

            return $movie;
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
        return [CategoryFixtures::class, DirectorFixtures::class, ScreenwriterFixtures::class, UserFixtures::class, CountryFixtures::class];
    }
}
