<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Headquarter $headquarter = null;

    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'program')]
    private Collection $documents;

    #[ORM\OneToMany(targetEntity: PersonInProgram::class, mappedBy: 'program')]
    private Collection $people;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->people = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

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

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setProgram($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getProgram() === $this) {
                $document->setProgram(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PersonInProgram>
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPeople(PersonInProgram $personInProgram): static
    {
        if (!$this->people->contains($personInProgram)) {
            $this->people->add($personInProgram);
            $personInProgram->setProgram($this);
        }

        return $this;
    }

    public function removePeople(PersonInProgram $personInProgram): static
    {
        if ($this->people->removeElement($personInProgram)) {
            // set the owning side to null (unless already changed)
            if ($personInProgram->getProgram() === $this) {
                $personInProgram->setProgram(null);
            }
        }

        return $this;
    }

    public function isActived()
    {
        $now = new \DateTime('NOW');

        if ($now < $this->getDateEnd()) {
            return true;
        }
        return false;
    }
}
