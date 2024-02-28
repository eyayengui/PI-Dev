<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
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

    #[ORM\ManyToOne(inversedBy: 'id_Q')]
    private ?Proposition $proposition = null;

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

    public function getProposition(): ?Proposition
    {
        return $this->proposition;
    }

    public function setProposition(?Proposition $proposition): static
    {
        $this->proposition = $proposition;

        return $this;
    }
}
