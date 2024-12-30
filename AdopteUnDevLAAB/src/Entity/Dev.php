<?php

namespace App\Entity;

use App\Repository\DevRepository;
use App\Utils\TraitClasses\EntityTimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevRepository::class)]
class Dev
{
    use EntityTimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenoms = null;

    #[ORM\Column(type: Types::TEXT,nullable: true)]
    private ?string $bibliographie = null;

    #[ORM\Column]
    private ?bool $visibilite = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $salaireMin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(string $prenoms): static
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getBibliographie(): ?string
    {
        return $this->bibliographie;
    }

    public function setBibliographie(string $bibliographie): static
    {
        $this->bibliographie = $bibliographie;

        return $this;
    }

    public function isVisibilite(): ?bool
    {
        return $this->visibilite;
    }

    public function setVisibilite(bool $visibilite): static
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getSalaireMin(): ?string
    {
        return $this->salaireMin;
    }

    public function setSalaireMin(string $salaireMin): static
    {
        $this->salaireMin = $salaireMin;

        return $this;
    }
}
