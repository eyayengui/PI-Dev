<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'activites')]
    private Collection $id_U;

    #[ORM\OneToMany(targetEntity: Programme::class, mappedBy: 'activite')]
    private Collection $id_P;

    public function __construct()
    {
        $this->id_U = new ArrayCollection();
        $this->id_P = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdU(): Collection
    {
        return $this->id_U;
    }

    public function addIdU(User $idU): static
    {
        if (!$this->id_U->contains($idU)) {
            $this->id_U->add($idU);
        }

        return $this;
    }

    public function removeIdU(User $idU): static
    {
        $this->id_U->removeElement($idU);

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
            $idP->setActivite($this);
        }

        return $this;
    }

    public function removeIdP(Programme $idP): static
    {
        if ($this->id_P->removeElement($idP)) {
            // set the owning side to null (unless already changed)
            if ($idP->getActivite() === $this) {
                $idP->setActivite(null);
            }
        }

        return $this;
    }
}
