<?php

namespace App\Entity;
use DateTime;
use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_c = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $remarques = null;

    #[ORM\Column(length: 255)]
    private ?string $pathologie = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichemedicale $Fichemedicale = null;

    #[ORM\Column(nullable: true)]
    private ?bool $confirmation = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idp = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->date_c;
    }

    public function setDateC(\DateTimeInterface $date_c): static
    {
        $this->date_c = $date_c;

        return $this;
    }

    public function getRemarques(): ?string
    {
        return $this->remarques;
    }

    public function setRemarques(?string $remarques): static
    {
        $this->remarques = $remarques;

        return $this;
    }

    public function getPathologie(): ?string
    {
        return $this->pathologie;
    }

    public function setPathologie(string $pathologie): static
    {
        $this->pathologie = $pathologie;

        return $this;
    }

    public function getFichemedicale(): ?Fichemedicale
    {
        return $this->Fichemedicale;
    }

    public function setFichemedicale(?Fichemedicale $Fichemedicale): static
    {
        $this->Fichemedicale = $Fichemedicale;

        return $this;
    }

    public function getConfirmation(): ?bool
    {
        return $this->confirmation;
    }

    public function setConfirmation(?bool $confirmation): static
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    public function getIdp(): ?User
    {
        return $this->idp;
    }

    public function setIdp(?User $idp): static
    {
        $this->idp = $idp;

        return $this;
    }

    public function getIdt(): ?User
    {
        return $this->idt;
    }

    public function setIdt(?User $idt): static
    {
        $this->idt = $idt;

        return $this;
    }
    
    public function isCompleted(): bool
    {
        $currentDate = new DateTime();
        return $this->date_c < $currentDate;
    }
}
