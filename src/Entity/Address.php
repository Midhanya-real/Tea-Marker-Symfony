<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Country()]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $city = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank()]
    #[Assert\Positive()]
    private ?int $house = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank()]
    #[Assert\Positive()]
    private ?int $apartment = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 6)]
    private ?int $post_code = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(User::class)]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getHouse(): ?int
    {
        return $this->house;
    }

    public function setHouse(int $house): static
    {
        $this->house = $house;

        return $this;
    }

    public function getApartment(): ?int
    {
        return $this->apartment;
    }

    public function setApartment(int $apartment): static
    {
        $this->apartment = $apartment;

        return $this;
    }

    public function getPostCode(): ?int
    {
        return $this->post_code;
    }

    public function setPostCode(int $post_code): static
    {
        $this->post_code = $post_code;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
