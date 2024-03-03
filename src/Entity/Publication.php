<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre_P = null;

    #[ORM\Column(length: 255)]
    private ?string $Description_P = null;

    #[ORM\Column(length: 255)]
    private ?string $Image_P = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date_P = null;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'publication')]
    private Collection $Commentaires;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ID_User = null;

    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'Publication')]
    private Collection $likes;

    public function __construct()
    {
        $this->Commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreP(): ?string
    {
        return $this->Titre_P;
    }

    public function setTitreP(string $Titre_P): static
    {
        $this->Titre_P = $Titre_P;

        return $this;
    }

    public function getDescriptionP(): ?string
    {
        return $this->Description_P;
    }

    public function setDescriptionP(string $Description_P): static
    {
        $this->Description_P = $Description_P;

        return $this;
    }

    public function getImageP(): ?string
    {
        return $this->Image_P;
    }

    public function setImageP(string $Image_P): static
    {
        $this->Image_P = $Image_P;

        return $this;
    }

    public function getDateP(): ?\DateTimeInterface
    {
        return $this->Date_P;
    }

    public function setDateP(?\DateTimeInterface $Date_P): static
    {
        $this->Date_P = $Date_P;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->Commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->Commentaires->contains($commentaire)) {
            $this->Commentaires->add($commentaire);
            $commentaire->setPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->Commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPublication() === $this) {
                $commentaire->setPublication(null);
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

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPublication($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPublication() === $this) {
                $like->setPublication(null);
            }
        }

        return $this;
    }
}
