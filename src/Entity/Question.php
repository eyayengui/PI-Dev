<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title_question = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Questionnaire $questionnaire = null;

    #[ORM\OneToMany(targetEntity: Proposition::class, mappedBy: 'question', cascade:["persist","remove"])]
    private Collection $propositions;

    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'question')]
    private Collection $answers;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?User $ID_User = null;

    public function __construct()
    {
        $this->propositions = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }
/*
    #[ORM\ManyToOne(inversedBy: 'id_Q')]
    private ?Proposition $proposition = null;
*/
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleQuestion(): ?string
    {
        return $this->title_question;
    }

    public function setTitleQuestion(string $title_question): static
    {
        $this->title_question = $title_question;

        return $this;
    }

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $questionnaire): static
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }
/*
    public function getProposition(): ?Proposition
    {
        return $this->proposition;
    }

    public function setProposition(?Proposition $proposition): static
    {
        $this->proposition = $proposition;

        return $this;
    }
    */

/**
 * @return Collection<int, Proposition>
 */
public function getPropositions(): Collection
{
    return $this->propositions;
}

public function addProposition(Proposition $proposition): static
{
    if (!$this->propositions->contains($proposition)) {
        $this->propositions->add($proposition);
        $proposition->setQuestion($this);
    }

    return $this;
}

public function removeProposition(Proposition $proposition): static
{
    if ($this->propositions->removeElement($proposition)) {
        // set the owning side to null (unless already changed)
        if ($proposition->getQuestion() === $this) {
            $proposition->setQuestion(null);
        }
    }

    return $this;
}

/**
 * @return Collection<int, Answer>
 */
public function getAnswers(): Collection
{
    return $this->answers;
}

public function addAnswer(Answer $answer): static
{
    if (!$this->answers->contains($answer)) {
        $this->answers->add($answer);
        $answer->setQuestion($this);
    }

    return $this;
}

public function removeAnswer(Answer $answer): static
{
    if ($this->answers->removeElement($answer)) {
        // set the owning side to null (unless already changed)
        if ($answer->getQuestion() === $this) {
            $answer->setQuestion(null);
        }
    }

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
