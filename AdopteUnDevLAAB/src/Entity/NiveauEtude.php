<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use App\Repository\NiveauEtudeRepository;
use App\Utils\TraitClasses\EntityTimestampableTrait;

#[ORM\Entity(repositoryClass: NiveauEtudeRepository::class)]
class NiveauEtude
{
    use EntityTimestampableTrait;
    #[ORM\Id] 
    #[ORM\Column(type: 'guid', unique: true)] 
    #[ORM\GeneratedValue(strategy: 'CUSTOM')] 
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)] 
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
