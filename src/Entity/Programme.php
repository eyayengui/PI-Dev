<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $but = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_L = null;

    #[ORM\ManyToOne(inversedBy: 'id_P')]
    private ?Activite $activite = null;

    #[ORM\ManyToOne(inversedBy: 'id_P')]
    private ?Exercice $exercice = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getBut(): ?string
    {
        return $this->but;
    }

    public function setBut(string $but): static
    {
        $this->but = $but;

        return $this;
    }

    public function getNomL(): ?string
    {
        return $this->nom_L;
    }

    public function setNomL(string $nom_L): static
    {
        $this->nom_L = $nom_L;

        return $this;
    }

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function setActivite(?Activite $activite): static
    {
        $this->activite = $activite;

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): static
    {
        $this->exercice = $exercice;

        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

}
