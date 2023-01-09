<?php

namespace App\Entity;

use function Symfony\Bridge\Twig\Extension\twig_is_selected_choice;

class WatchlistCard
{
    private String $id;
    private String $idTMDB;
    private String $title;
    private String $releaseDate;
    private String $posterPath;
    private String $pathName;
    private String $type;
    private ?String $status=null;




    public function __construct(String $_id, String $_idTMDB, String $_title, String $_releaseDate, String $_posterPath, String $_pathName, String $_type, ?String $_status)
    {
        $this->id = $_id;
        $this->idTMDB = $_idTMDB;
        $this->title = $_title;
        $this->releaseDate = $_releaseDate;
        $this->posterPath = $_posterPath;
        $this->pathName = $_pathName;
        $this->type = $_type;
        $this->status = $_status;
    }

    /**
     * @return String
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param String $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
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
    public function getIdTMDB(): string
    {
        return $this->idTMDB;
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

}