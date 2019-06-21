<?php
/**
 * Director entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectorRepository")
 */
class Director
{
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
     * @Assert\Type("string")
     * @Assert\Length(
     *     max="128",
     *     min="3",
     *     )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="director")
     */
    private $movies;

    /**
     * Director constructor.
     */
    public function __construct()
    {
        $this->movies = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Director
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    /**
     * @param Movie $movie
     *
     * @return Director
     */
    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->setDirector($this);
        }

        return $this;
    }

    /**
     * @param Movie $movie
     *
     * @return Director
     */
    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
            // set the owning side to null (unless already changed)
            if ($movie->getDirector() === $this) {
                $movie->setDirector(null);
            }
        }

        return $this;
    }
}
