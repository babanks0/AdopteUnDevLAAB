<?php

namespace App\Entity;

use App\Repository\CandidactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidactureRepository::class)]
class Candidacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Poste $poste = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(Poste $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
