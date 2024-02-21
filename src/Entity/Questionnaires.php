<?php

namespace App\Entity;

use App\Repository\QuestionnairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionnairesRepository::class)]
class Questionnaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title_questionnaires = null;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'questionnaires')]
    private Collection $Questions;

    public function __construct()
    {
        $this->Questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleQuestionnaires(): ?string
    {
        return $this->title_questionnaires;
    }

    public function setTitleQuestionnaires(string $title_questionnaires): static
    {
        $this->title_questionnaires = $title_questionnaires;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->Questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->Questions->contains($question)) {
            $this->Questions->add($question);
            $question->setQuestionnaires($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->Questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuestionnaires() === $this) {
                $question->setQuestionnaires(null);
            }
        }

        return $this;
    }
}
