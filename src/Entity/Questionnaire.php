<?php

namespace App\Entity;

use App\Repository\QuestionnaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionnaireRepository::class)]
class Questionnaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title_questionnaire = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_U = null;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'questionnaire')]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleQuestionnaire(): ?string
    {
        return $this->title_questionnaire;
    }

    public function setTitleQuestionnaire(string $title_questionnaire): static
    {
        $this->title_questionnaire = $title_questionnaire;

        return $this;
    }

    public function getIdU(): ?User
    {
        return $this->id_U;
    }

    public function setIdU(User $id_U): static
    {
        $this->id_U = $id_U;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuestionnaire() === $this) {
                $question->setQuestionnaire(null);
            }
        }

        return $this;
    }
}
