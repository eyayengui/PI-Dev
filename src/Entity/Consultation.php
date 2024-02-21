<?php

namespace App\Entity;
use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotBlank]
    #[Assert\Type(\DateTime::class)]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_c = null;

 
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $remarques = null;

    #[Assert\NotBlank]
    #[Assert\Regex(
    pattern: '/^[a-zA-Z\s]+$/',
    message: 'Pathologie should contain only letters.'
    )]
#[ORM\Column(length: 255, nullable: true)]
private ?string $pathologie = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: true, options: ["default" => 0])]
    private ?Fichemedicale $Fichemedicale = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $idp = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $idt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $confirmation = null;

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

    public function setPathologie(?string $pathologie): static
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

    public function getIdp(): ?int
    {
        return $this->idp;
    }

    public function setIdp(int $idp): static
    {
        $this->idp = $idp;

        return $this;
    }

    public function getIdt(): ?int
    {
        return $this->idt;
    }

    public function setIdt(int $idt): static
    {
        $this->idt = $idt;

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
}
