<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Serie $serie = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

    #[ORM\Column]
    private ?string $idTMDB = null;

    #[ORM\Column(length: 255)]
    private ?string $air_date = null;

    #[ORM\Column(length: 255)]
    private ?string $poster_path = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getIdTMDB(): ?string
    {
        return $this->idTMDB;
    }

    public function setIdTMDB(string $idTMDB): self
    {
        $this->idTMDB = $idTMDB;

        return $this;
    }

    public function getAirDate(): ?string
    {
        return $this->air_date;
    }

    public function setAirDate(string $air_date): self
    {
        $this->air_date = $air_date;

        return $this;
    }

    public function getPosterPath(): ?string
    {
        return $this->poster_path;
    }

    public function setPosterPath(string $poster_path): self
    {
        $this->poster_path = $poster_path;

        return $this;
    }
}
