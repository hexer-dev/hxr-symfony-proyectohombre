<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\UniqueConstraint(fields: ['nif', 'headquarter'])]
#[UniqueEntity(
    fields: ['nif', 'headquarter'],
    message: 'Este usuario ya está registrado en la sede.',
    errorPath: 'nif',
)]
class Person
{
    public const GENDER = [
        'FEMALE' => 'Femenino',
        'MALE' => 'Masculino'
    ]; 

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9)]
    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'El nif debe rellenarse',)]
    private ?string $nif = null;

    #[ORM\ManyToOne(inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Headquarter $headquarter = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'El nombre debe rellenarse',)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank(message: 'Los apellidos debe rellenarse',)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    public function __toString()
    {
        return sprintf("%, %", $this->getLastname(), $this->getName());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(string $nif): static
    {
        $this->nif = $nif;

        return $this;
    }

    public function getHeadquarter(): ?Headquarter
    {
        return $this->headquarter;
    }

    public function setHeadquarter(?Headquarter $headquarter): static
    {
        $this->headquarter = $headquarter;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }
}
