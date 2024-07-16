<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserSecurityRepository;

#[ORM\Entity(repositoryClass: UserSecurityRepository::class)]
#[ORM\Table(name: 'user_security')]
final class UserSecurity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $ip = null;

    #[ORM\Column]
    private ?int $security = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getSecurity(): ?int
    {
        return $this->security;
    }

    public function setSecurity(int $security): static
    {
        $this->security = $security;

        return $this;
    }
}
