<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use App\Utils\TraitClasses\EntityTimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    use EntityTimestampableTrait;
    #[ORM\Id] 
    #[ORM\Column(type: 'guid', unique: true)] 
    #[ORM\GeneratedValue(strategy: 'CUSTOM')] 
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)] 
    private ?string $id = null; 

    #[ORM\Column(length: 255)]
    private ?string $raisonSociale = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    private ?string $description = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(string $raisonSociale): static
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

}
