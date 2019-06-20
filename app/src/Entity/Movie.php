<?php
/**
 * Movie entity.
 */

namespace App\Entity {
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Mapping\Annotation as Gedmo;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Movie .
     *
     * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
     * @ORM\Table(
     *     name="movie",
     * )
     *
     * @UniqueEntity(fields={"id"})
     */
    class Movie
    {
        /**
         * Use constants to define configuration options that rarely change instead
         * of specifying them in app/config/config.yml.
         * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options.
         *
         * @constant int NUMBER_OF_ITEMS
         */
        const NUMBER_OF_ITEMS = 6;

        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=128)
         *
         * @Assert\NotBlank
         * @Assert\Length(
         *     min="3",
         *     max="128",
         * )
         */
        private $title;

        /**
         * @ORM\Column(type="integer")
         *
         * @Assert\NotBlank
         * @Assert\Type("integer")
         * @Assert\Range(
         *      min = 1890,
         *      max = 2025,
         * )
         */
        private $year;

        /**
         * @ORM\Column(type="integer")
         *
         * @Assert\NotBlank
         * @Assert\Range(
         *      min = 1,
         *      max = 10,
         * )
         */
        private $rate;

        /**
         * @ORM\Column(type="integer")
         *
         * @Assert\NotBlank
         * @Assert\Type("integer")
         * @Assert\LessThanOrEqual(
         *     value = 3000000000
         * )
         */
        private $boxoffice;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="movies")
         * @ORM\JoinColumn(nullable=false)
         *
         * @Assert\NotBlank
         */
        private $category;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\Screenwriter", inversedBy="movies")
         * @ORM\JoinColumn(nullable=false)
         *
         * @Assert\NotBlank
         * @Assert\Valid()
         */
        private $screenwriter;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\Director", inversedBy="movies")
         * @ORM\JoinColumn(nullable=false)
         *
         * @Assert\NotBlank
         * @Assert\Valid()
         */
        private $director;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="movies")
         * @ORM\JoinColumn(nullable=false)
         */
        private $author;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="movie", orphanRemoval=true)
         */
        private $reviews;

        /**
         * Code.
         *
         * @var string
         *
         * @ORM\Column(
         *     type="string",
         *     length=64
         * )
         * @Gedmo\Slug(fields={"title"})
         */
        private $code;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="movies", cascade={"persist"})
         * @ORM\JoinColumn(nullable=false)
         *
         * @Assert\NotBlank
         * @Assert\Valid()
         */
        private $country;

        /**
         * @ORM\ManyToMany(targetEntity="App\Entity\Actor", inversedBy="movies")
         *
         * @Assert\Valid()
         */
        private $actors;

        /**
         * @ORM\OneToOne(targetEntity="App\Entity\Photo", mappedBy="movie", cascade={"persist", "remove"})
         */
        private $photo;

        /**
         * Movie constructor.
         */
        public function __construct()
        {
            $this->reviews = new ArrayCollection();
            $this->actors = new ArrayCollection();
        }

        /**
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * @return string|null
         */
        public function getTitle(): ?string
        {
            return $this->title;
        }

        /**
         * @param string $title
         *
         * @return Movie
         */
        public function setTitle(string $title): self
        {
            $this->title = $title;

            return $this;
        }

        /**
         * @return int|null
         */
        public function getYear(): ?int
        {
            return $this->year;
        }

        /**
         * @param int $year
         *
         * @return Movie
         */
        public function setYear(int $year): self
        {
            $this->year = $year;

            return $this;
        }

        /**
         * @return int|null
         */
        public function getRate(): ?int
        {
            return $this->rate;
        }

        /**
         * @param int $rate
         *
         * @return Movie
         */
        public function setRate(int $rate): self
        {
            $this->rate = $rate;

            return $this;
        }

        /**
         * @return int|null
         */
        public function getBoxoffice(): ?int
        {
            return $this->boxoffice;
        }

        /**
         * @param int $boxoffice
         *
         * @return Movie
         */
        public function setBoxoffice(int $boxoffice): self
        {
            $this->boxoffice = $boxoffice;

            return $this;
        }

        /**
         * @return Category|null
         */
        public function getCategory(): ?Category
        {
            return $this->category;
        }

        /**
         * @param Category|null $category
         *
         * @return Movie
         */
        public function setCategory(?Category $category): self
        {
            $this->category = $category;

            return $this;
        }

        /**
         * @return Screenwriter|null
         */
        public function getScreenwriter(): ?Screenwriter
        {
            return $this->screenwriter;
        }

        /**
         * @param Screenwriter|null $screenwriter
         *
         * @return Movie
         */
        public function setScreenwriter(?Screenwriter $screenwriter): self
        {
            $this->screenwriter = $screenwriter;

            return $this;
        }

        /**
         * @return Director|null
         */
        public function getDirector(): ?Director
        {
            return $this->director;
        }

        /**
         * @param Director|null $director
         *
         * @return Movie
         */
        public function setDirector(?Director $director): self
        {
            $this->director = $director;

            return $this;
        }

        /**
         * @return User|null
         */
        public function getAuthor(): ?User
        {
            return $this->author;
        }

        /**
         * @param User|null $author
         *
         * @return Movie
         */
        public function setAuthor(?User $author): self
        {
            $this->author = $author;

            return $this;
        }

        /**
         * @return Collection|Review[]
         */
        public function getReviews(): Collection
        {
            return $this->reviews;
        }

        /**
         * @param Review $review
         *
         * @return Movie
         */
        public function addReview(Review $review): self
        {
            if (!$this->reviews->contains($review)) {
                $this->reviews[] = $review;
                $review->setMovie($this);
            }

            return $this;
        }

        /**
         * @param Review $review
         *
         * @return Movie
         */
        public function removeReview(Review $review): self
        {
            if ($this->reviews->contains($review)) {
                $this->reviews->removeElement($review);
                // set the owning side to null (unless already changed)
                if ($review->getMovie() === $this) {
                    $review->setMovie(null);
                }
            }

            return $this;
        }

        /**
         * @return string|null
         */
        public function getCode(): ?string
        {
            return $this->code;
        }

        /**
         * @param string|null $code
         *
         * @return Movie
         */
        public function setCode(?string $code): self
        {
            $this->code = $code;

            return $this;
        }

        /**
         * @return Country|null
         */
        public function getCountry(): ?Country
        {
            return $this->country;
        }

        /**
         * @param Country|null $country
         *
         * @return Movie
         */
        public function setCountry(?Country $country): self
        {
            $this->country = $country;

            return $this;
        }

        /**
         * @return Collection|Actor[]
         */
        public function getActors(): Collection
        {
            return $this->actors;
        }

        /**
         * @param Actor $actor
         *
         * @return Movie
         */
        public function addActor(Actor $actor): self
        {
            if (!$this->actors->contains($actor)) {
                $this->actors[] = $actor;
            }

            return $this;
        }

        /**
         * @param Actor $actor
         *
         * @return Movie
         */
        public function removeActor(Actor $actor): self
        {
            if ($this->actors->contains($actor)) {
                $this->actors->removeElement($actor);
            }

            return $this;
        }

        /**
         * @return Photo|null
         */
        public function getPhoto(): ?Photo
        {
            return $this->photo;
        }

        /**
         * @param Photo|null $photo
         *
         * @return Movie
         */
        public function setPhoto(?Photo $photo): self
        {
            $this->photo = $photo;

            // set (or unset) the owning side of the relation if necessary
            $newMovie = null === $photo ? null : $this;
            if ($newMovie !== $photo->getMovie()) {
                $photo->setMovie($newMovie);
            }

            return $this;
        }
    }
}
