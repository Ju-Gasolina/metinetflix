<?php

namespace App\Entity;

use phpDocumentor\Reflection\Types\Integer;

class Card
{
    private String $id;
    private String $title;
    private String $releaseDate;
    private String $posterPath;
    private String $pathName;
    private String $type;
    private ?float $popularity = null;
    private ?float $markAverage = null;
    private bool $isWatchlistItem;

    public function __construct(String $_id, String $_title, String $_releaseDate, String $_posterPath, String $_pathName, String $_type, ?float $_popularity, ?float $_markAverage)
    {
        $this->title = $_title;
        $this->id = $_id;
        $this->releaseDate = $_releaseDate;
        $this->posterPath = $_posterPath;
        $this->pathName = $_pathName;
        $this->type = $_type;
        $this->popularity = $_popularity;
        $this->markAverage = $_markAverage;
        $this->isWatchlistItem = false;

    }

    /**
     * @return float|null
     */
    public function getPopularity(): ?float
    {
        return $this->popularity;
    }

    /**
     * @param float|null $popularity
     */
    public function setPopularity(?float $popularity): void
    {
        $this->popularity = $popularity;
    }

    /**
     * @return float|null
     */
    public function getMarkAverage(): ?float
    {
        return $this->markAverage;
    }

    /**
     * @param float|null $markAverage
     */
    public function setMarkAverage(?float $markAverage): void
    {
        $this->markAverage = $markAverage;
    }

    /**
     * @return String
     */
    public function getPathName(): string
    {
        return $this->pathName;
    }

    /**
     * @return String
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return String
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return String
     */
    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    /**
     * @return String
     */
    public function getPosterPath(): string
    {
        return $this->posterPath;
    }

    /**
     * @return String
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function getIsWatchlistItem(): bool
    {
        return $this->isWatchlistItem;
    }

    /**
     * @param bool $isWatchlistItem
     */
    public function setIsWatchlistItem(?bool $isWatchlistItem): void
    {
        $this->isWatchlistItem = $isWatchlistItem;
    }
}