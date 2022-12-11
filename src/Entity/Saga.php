<?php

namespace App\Entity;

use App\Repository\SagaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SagaRepository::class)]
class Saga
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $idTMDB = null;

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

    public function getIdTMDB(): ?int
    {
        return $this->idTMDB;
    }

    public function setIdTMDB(int $idTMDB): self
    {
        $this->idTMDB = $idTMDB;

        return $this;
    }
}
