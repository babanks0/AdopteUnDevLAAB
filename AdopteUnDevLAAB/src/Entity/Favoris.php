<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FavorisRepository;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use App\Utils\TraitClasses\EntityTimestampableTrait;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
class Favoris
{
    use EntityTimestampableTrait;
    #[ORM\Id] 
    #[ORM\Column(type: 'guid', unique: true)] 
    #[ORM\GeneratedValue(strategy: 'CUSTOM')] 
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)] 
    private ?string $id = null;

    #[ORM\ManyToOne]
    private ?Poste $poste = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): static
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
