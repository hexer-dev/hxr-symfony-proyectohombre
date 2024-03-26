<?php

namespace App\Entity;

use App\Repository\PersonInProgramRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

#[ORM\Entity(repositoryClass: PersonInProgramRepository::class)]
class PersonInProgram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\ManyToOne(inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Program $program = null;

    #[ORM\Column(nullable: true)]
    private ?bool $reductIrpf = null;

    #[ORM\ManyToMany(targetEntity: Timetable::class, mappedBy: 'personInProgram', cascade: ['persist', 'remove'])]
    private Collection $timetables;

    public function __construct()
    {
        $this->timetables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;

        return $this;
    }

    public function getProgram(): ?Program
    {
        return $this->program;
    }

    public function setProgram(?Program $program): static
    {
        $this->program = $program;

        return $this;
    }

    public function isReductIrpf(): ?bool
    {
        return $this->reductIrpf;
    }

    public function setReductIrpf(?bool $reductIrpf): static
    {
        $this->reductIrpf = $reductIrpf;

        return $this;
    }

    /**
     * @return Collection<int, Timetable>
     */
    public function getTimetables(): Collection
    {
        return $this->timetables;
    }

    public function addTimetable(Timetable $timetable): static
    {
        if (!$this->timetables->contains($timetable)) {
            $this->timetables->add($timetable);
            $timetable->addPersonInProgram($this);
        }

        return $this;
    }

    public function removeTimetable(Timetable $timetable): static
    {
        if ($this->timetables->removeElement($timetable)) {
            $timetable->removePersonInProgram($this);
        }

        return $this;
    }

    public function hasOpenTimetable()
    {
        $timetables = $this->getTimetables();

        foreach ($timetables as $timetable) {
            if ($timetable->isOpen()) {
                return true;
            }
        }

        return false;
    }
}
