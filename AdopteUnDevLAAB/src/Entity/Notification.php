<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Poste $post = null;

    #[ORM\Column]
    private ?bool $view = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPost(): ?Poste
    {
        return $this->post;
    }

    public function setPost(?Poste $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function isView(): ?bool
    {
        return $this->view;
    }

    public function setView(bool $view): static
    {
        $this->view = $view;

        return $this;
    }
}
