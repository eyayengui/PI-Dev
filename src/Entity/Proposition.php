<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PropositionRepository::class)]
class Proposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le titre ne peut pas être vide.")]
    #[Assert\Type(
        type:"string",
        message:"La valeur {{ value }} n'est pas un type {{ type }} valide.")
     ]
    private ?string $title_proposition = null;
    #[ORM\Column(type:"integer")]
    private $score;
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
    
   
   public function getScore(): ?int
   {
       return $this->score;
   }
   
   public function setScore(int $score): self
   {
       $this->score = $score;
       return $this;
   }
}
