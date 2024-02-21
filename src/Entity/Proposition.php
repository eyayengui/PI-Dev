<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropositionRepository::class)]
class Proposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title_proposition = null;

    #[ORM\ManyToOne(inversedBy: 'Questions')]
    private ?Question $id_Q = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleProposition(): ?string
    {
        return $this->title_proposition;
    }

    public function setTitleProposition(string $title_proposition): static
    {
        $this->title_proposition = $title_proposition;

        return $this;
    }

    public function getIdQ(): ?Question
    {
        return $this->id_Q;
    }

    public function setIdQ(?Question $id_Q): static
    {
        $this->id_Q = $id_Q;

        return $this;
    }
}
