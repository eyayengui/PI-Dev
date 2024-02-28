<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/\?$/",
        message: "Le titre de la question doit se terminer par un point d'interrogation."
    )]
    private ?string $title_question = null;

    #[ORM\OneToMany(targetEntity: Proposition::class, mappedBy: 'id_Q', cascade:["persist","remove"])]
    private Collection $Questions;

    #[ORM\ManyToOne(inversedBy: 'Questions')]
    private ?Questionnaires $questionnaires = null;

    #[ORM\OneToMany(targetEntity: Answer::class, mappedBy: 'question')]
    private Collection $answers;

    public function __construct()
    {
        $this->Questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

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
    public function __toString()
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Proposition>
     */
    public function getQuestions(): Collection
    {
        return $this->Questions;
    }

    public function addQuestion(Proposition $question): static
    {
        if (!$this->Questions->contains($question)) {
            $this->Questions->add($question);
            $question->setIdQ($this);
        }

        return $this;
    }

    public function removeQuestion(Proposition $question): static
    {
        if ($this->Questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getIdQ() === $this) {
                $question->setIdQ(null);
            }
        }

        return $this;
    }

    public function getQuestionnaires(): ?Questionnaires
    {
        return $this->questionnaires;
    }

    public function setQuestionnaires(?Questionnaires $questionnaires): static
    {
        $this->questionnaires = $questionnaires;

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
}
