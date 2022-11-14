<?php

namespace App\Entity;

use App\Repository\WatchlistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WatchlistRepository::class)]
class Watchlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $total_duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTotalDuration(): ?int
    {
        return $this->total_duration;
    }

    public function setTotalDuration(int $total_duration): self
    {
        $this->total_duration = $total_duration;

        return $this;
    }
}
