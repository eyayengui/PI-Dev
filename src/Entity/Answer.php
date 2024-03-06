<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Proposition $proposition_Choisie = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?User $ID_User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getPropositionChoisie(): ?Proposition
    {
        return $this->proposition_Choisie;
    }

    public function setPropositionChoisie(Proposition $proposition_Choisie): static
    {
        $this->proposition_Choisie = $proposition_Choisie;

        return $this;
    }

    public function getIDUser(): ?User
    {
        return $this->ID_User;
    }

    public function setIDUser(?User $ID_User): static
    {
        $this->ID_User = $ID_User;

        return $this;
    }
}
