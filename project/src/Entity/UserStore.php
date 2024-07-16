<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserStoreRepository;

#[ORM\Entity(repositoryClass: UserStoreRepository::class)]
#[ORM\Table(name: 'user_store')]
final class UserStore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $store_id = null;

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

    public function getStoreId(): ?int
    {
        return $this->store_id;
    }

    public function setStoreId(int $store_id): static
    {
        $this->store_id = $store_id;

        return $this;
    }
}
