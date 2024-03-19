<?php

namespace App\Entity;

use App\Repository\TimetableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimetableRepository::class)]
class Timetable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne]
    private ?User $updatedBy = null;

    #[ORM\ManyToMany(targetEntity: PersonInProgram::class, inversedBy: 'timetables')]
    private Collection $personInProgram;

    public function __construct()
    {
        $this->personInProgram = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return Collection<int, PersonInProgram>
     */
    public function getPersonInProgram(): Collection
    {
        return $this->personInProgram;
    }

    public function addPersonInProgram(PersonInProgram $personInProgram): static
    {
        if (!$this->personInProgram->contains($personInProgram)) {
            $this->personInProgram->add($personInProgram);
        }

        return $this;
    }

    public function removePersonInProgram(PersonInProgram $personInProgram): static
    {
        $this->personInProgram->removeElement($personInProgram);

        return $this;
    }

    public function isOpen()
    {
        if (
            $this->getDateEnd() === null
            && $this->getDateStart() !== null
        ) {
            return true;
        }
        return false;
    }
}
