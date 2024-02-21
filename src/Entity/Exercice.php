<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_coach = null;

    #[ORM\Column(length: 255)]
    private ?string $duree = null;

    #[ORM\OneToMany(targetEntity: Programme::class, mappedBy: 'exercice')]
    private Collection $id_P;

    public function __construct()
    {
        $this->id_P = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCoach(): ?string
    {
        return $this->nom_coach;
    }

    public function setNomCoach(string $nom_coach): static
    {
        $this->nom_coach = $nom_coach;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getIdP(): Collection
    {
        return $this->id_P;
    }

    public function addIdP(Programme $idP): static
    {
        if (!$this->id_P->contains($idP)) {
            $this->id_P->add($idP);
            $idP->setExercice($this);
        }

        return $this;
    }

    public function removeIdP(Programme $idP): static
    {
        if ($this->id_P->removeElement($idP)) {
            // set the owning side to null (unless already changed)
            if ($idP->getExercice() === $this) {
                $idP->setExercice(null);
            }
        }

        return $this;
    }
}
