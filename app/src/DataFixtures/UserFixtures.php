<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Password encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(15, 'user', function () {

            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'user1234'));
            $user->setRoles(['ROLE_USER']);


            return $user;
        });

        $this->createMany(4, 'admin', function () {

            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin1234'));
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);


            return $user;
        });

        $manager->flush();
    }
}
