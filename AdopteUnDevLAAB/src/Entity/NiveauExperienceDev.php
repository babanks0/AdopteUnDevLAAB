<?php

namespace App\Entity;

use App\Repository\NiveauExperienceDevRepository;
use App\Utils\TraitClasses\EntityTimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NiveauExperienceDevRepository::class)]
class NiveauExperienceDev
{
    use EntityTimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dev $dev = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?NiveauExperience $niveauExperience = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDev(): ?Dev
    {
        return $this->dev;
    }

    public function setDev(?Dev $dev): static
    {
        $this->dev = $dev;

        return $this;
    }

    public function getNiveauExperience(): ?NiveauExperience
    {
        return $this->niveauExperience;
    }

    public function setNiveauExperience(?NiveauExperience $niveauExperience): static
    {
        $this->niveauExperience = $niveauExperience;

        return $this;
    }
}
