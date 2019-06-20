<?php
/**
 * UserData entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserDataRepository")
 */
class UserData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Regex("/[A-Za-z]+/")
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\Type(
     *     type="integer",
     * )
     * @Assert\Range(
     *      min = 7,
     *      max = 130,
     * )
     */
    private $age;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="userdata", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Valid()
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Regex("/[A-Za-z]+/")
     * @Assert\Length(
     *     min="3",
     *     max="64",
     *     )
     */
    private $firstName;

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
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return UserData
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int $age
     *
     * @return UserData
     */
    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserData
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return UserData
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }
}
