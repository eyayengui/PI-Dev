<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\FichemedicaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichemedicaleRepository::class)]
class Fichemedicale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $id_p = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $id_t = null;

    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $derniere_maj = null;

    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'Fichemedicale')]
    private Collection $consultations;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdP(): ?int
    {
        return $this->id_p;
    }

    public function setIdP(int $id_p): static
    {
        $this->id_p = $id_p;

        return $this;
    }

    public function getIdT(): ?int
    {
        return $this->id_t;
    }

    public function setIdT(int $id_t): static
    {
        $this->id_t = $id_t;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDerniereMaj(): ?\DateTimeInterface
    {
        return $this->derniere_maj;
    }

    public function setDerniereMaj(\DateTimeInterface $derniere_maj): static
    {
        $this->derniere_maj = $derniere_maj;

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setFichemedicale($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            if ($consultation->getFichemedicale() === $this) {
                $consultation->setFichemedicale(null);
            }
        }

        return $this;
    }
}
