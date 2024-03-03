<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(inversedBy: 'propositions')]
    private ?Question $question = null;

    #[ORM\Column]
    private ?int $score = null;
/*
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'proposition')]
    private Collection $id_Q;
*//*
    public function __construct()
    {
        $this->id_Q = new ArrayCollection();
    }
*/
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
/*
    /**
     * @return Collection<int, Question>
     */
    /*
    public function getIdQ(): Collection
    {
        return $this->id_Q;
    }

    public function addIdQ(Question $idQ): static
    {
        if (!$this->id_Q->contains($idQ)) {
            $this->id_Q->add($idQ);
            $idQ->setProposition($this);
        }

        return $this;
    }

    public function removeIdQ(Question $idQ): static
    {
        if ($this->id_Q->removeElement($idQ)) {
            // set the owning side to null (unless already changed)
            if ($idQ->getProposition() === $this) {
                $idQ->setProposition(null);
            }
        }

        return $this;
    }
*/

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }
}
