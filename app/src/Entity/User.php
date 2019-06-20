<?php
/**
 * User entity.
 */

namespace App\Entity {
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Validator\Constraints as Assert;
    use Doctrine\Common\Collections\ArrayCollection;
    use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

    /**
     * Class User.
     *
     * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
     *
     * @UniqueEntity(fields={"email"})
     */
    class User implements UserInterface
    {
        /**
         * Use constants to define configuration options that rarely change instead
         * of specifying them in app/config/config.yml.
         * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options.
         *
         * @constant int NUMBER_OF_ITEMS
         */
        const NUMBER_OF_ITEMS = 3;
        /**
         * Role user.
         *
         * @var string
         */
        const ROLE_USER = 'ROLE_USER';

        /**
         * Role admin.
         *
         * @var string
         */
        const ROLE_ADMIN = 'ROLE_ADMIN';

        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * E-mail.
         *
         * @var string
         *
         * @ORM\Column(
         *     type="string",
         *     length=128,
         * )
         *
         * @Assert\NotBlank
         * @Assert\Email
         */
        private $email;

        /**
         * Password.
         *
         * @ORM\Column(type="string", length=255)
         *
         * @Assert\NotBlank
         * @Assert\Length(
         *     min="6",
         *     max="255",
         * )
         */
        private $password;

        /**
         * @ORM\OneToOne(targetEntity="App\Entity\UserData", mappedBy="user", cascade={"persist", "remove"})
         *
         * @Assert\Valid()
         */
        private $userdata;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="author", orphanRemoval=true)
         */
        private $movies;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="author", orphanRemoval=true)
         */
        private $reviews;

        /**
         * @ORM\Column(type="array")
         */
        private $roles = [];

        /**
         * User constructor.
         */
        public function __construct()
        {
            $this->movies = new ArrayCollection();
            $this->reviews = new ArrayCollection();
        }

        /**
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Getter for the Password.
         *
         * @return string|null Password
         */
        public function getPassword(): ?string
        {
            return $this->password;
        }

        /**
         * Setter for the Password.
         *
         * @param string $password Password
         */
        public function setPassword(string $password): void
        {
            $this->password = $password;
        }

        /**
         * Getter for the E-mail.
         *
         * @return string|null E-mail
         */
        public function getEmail(): ?string
        {
            return $this->email;
        }

        /**
         * Setter for the E-mail.
         *
         * @param string $email E-mail
         */
        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        /**
         * @return UserData|null
         */
        public function getUserData(): ?UserData
        {
            return $this->userdata;
        }

        /**
         * @param UserData $userdata
         *
         * @return User
         */
        public function setUserData(UserData $userdata): self
        {
            $this->userdata = $userdata;

            // set the owning side of the relation if necessary
            if ($this !== $userdata->getUser()) {
                $userdata->setUser($this);
            }

            return $this;
        }

        /**
         * @return ArrayCollection
         */
        public function getMovies(): ArrayCollection
        {
            return $this->movies;
        }

        /**
         * @param Movie $movie
         *
         * @return User
         */
        public function addMovie(Movie $movie): self
        {
            if (!$this->movies->contains($movie)) {
                $this->movies[] = $movie;
                $movie->setAuthor($this);
            }

            return $this;
        }

        /**
         * @param Movie $movie
         *
         * @return User
         */
        public function removeMovie(Movie $movie): self
        {
            if ($this->movies->contains($movie)) {
                $this->movies->removeElement($movie);
                // set the owning side to null (unless already changed)
                if ($movie->getAuthor() === $this) {
                    $movie->setAuthor(null);
                }
            }

            return $this;
        }

        /**
         * @return ArrayCollection
         */
        public function getReviews(): ArrayCollection
        {
            return $this->reviews;
        }

        /**
         * @param Review $review
         *
         * @return User
         */
        public function addReview(Review $review): self
        {
            if (!$this->reviews->contains($review)) {
                $this->reviews[] = $review;
                $review->setAuthor($this);
            }

            return $this;
        }

        /**
         * @param Review $review
         *
         * @return User
         */
        public function removeReview(Review $review): self
        {
            if ($this->reviews->contains($review)) {
                $this->reviews->removeElement($review);
                // set the owning side to null (unless already changed)
                if ($review->getAuthor() === $this) {
                    $review->setAuthor(null);
                }
            }

            return $this;
        }

        /**
         * Getter for the Roles.
         *
         * @return array Roles
         */
        public function getRoles(): array
        {
            $roles = $this->roles;
            // guarantee every user at least has ROLE_USER
            $roles[] = static::ROLE_USER;

            return array_unique($roles);
        }

        /**
         * Setter for the Roles.
         *
         * @param array $roles Roles
         */
        public function setRoles(array $roles): void
        {
            $this->roles = $roles;
        }

        /**
         * @see UserInterface
         */
        public function getSalt()
        {
            // not needed when using bcrypt or argon
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials()
        {
            // If you store any temporary, sensitive data on the user, clear it here
            // $this->plainPassword = null;
        }

        /**
         * {@inheritdoc}
         *
         * @see UserInterface
         *
         * @return string User name
         */
        public function getUsername(): string
        {
            return (string) $this->email;
        }
    }
}
