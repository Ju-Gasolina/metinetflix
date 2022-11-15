<?php

namespace App\Entity;

use App\Repository\WatchlistItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WatchlistItemRepository::class)]
class WatchlistItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Watchlist $watchlist = null;

    #[ORM\Column(length: 255)]
    private ?string $item_type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Serie $serie = null;

    #[ORM\ManyToOne]
    private ?Season $season = null;

    #[ORM\ManyToOne]
    private ?Episode $episode = null;

    #[ORM\ManyToOne]
    private ?Saga $saga = null;

    #[ORM\ManyToOne]
    private ?Movie $movie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $finish_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $episode_progress = null;

    #[ORM\Column(nullable: true)]
    private ?int $mark = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $personnal_note = null;

    public function __construct()
    {
        $this->episode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWatchlist(): ?Watchlist
    {
        return $this->watchlist;
    }

    public function setWatchlist(Watchlist $watchlist): self
    {
        $this->watchlist = $watchlist;

        return $this;
    }

    public function getItemType(): ?string
    {
        return $this->item_type;
    }

    public function setItemType(string $item_type): self
    {
        $this->item_type = $item_type;

        return $this;
    }

    public function getSerie(): ?Serie
    {
        return $this->serie;
    }

    public function setSerie(?Serie $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    public function setEpisode(?Episode $episode): self
    {
        $this->episode = $episode;

        return $this;
    }

    public function getSaga(): ?Saga
    {
        return $this->saga;
    }

    public function setSaga(?Saga $saga): self
    {
        $this->saga = $saga;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finish_date;
    }

    public function setFinishDate(?\DateTimeInterface $finish_date): self
    {
        $this->finish_date = $finish_date;

        return $this;
    }

    public function getEpisodeProgress(): ?int
    {
        return $this->episode_progress;
    }

    public function setEpisodeProgress(?int $episode_progress): self
    {
        $this->episode_progress = $episode_progress;

        return $this;
    }

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(?int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getPersonnalNote(): ?string
    {
        return $this->personnal_note;
    }

    public function setPersonnalNote(?string $personnal_note): self
    {
        $this->personnal_note = $personnal_note;

        return $this;
    }
}
