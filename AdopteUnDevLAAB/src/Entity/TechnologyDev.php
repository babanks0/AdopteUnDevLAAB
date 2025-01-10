<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use App\Repository\TechnologyDevRepository;
use App\Utils\TraitClasses\EntityTimestampableTrait;

#[ORM\Entity(repositoryClass: TechnologyDevRepository::class)]
class TechnologyDev
{
    use EntityTimestampableTrait;
    #[ORM\Id] 
    #[ORM\Column(type: 'guid', unique: true)] 
    #[ORM\GeneratedValue(strategy: 'CUSTOM')] 
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)] 
    private ?string $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Technology $technology = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;



    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTechnology(): ?Technology
    {
        return $this->technology;
    }

    public function setTechnology(?Technology $technology): static
    {
        $this->technology = $technology;

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
