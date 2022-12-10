<?php

namespace App\Entity;

class Card
{
    private String $id;
    private String $title;
    private String $releaseDate;
    private String $posterPath;
    private String $pathName;
    private String $type;




    public function __construct(String $_id, String $_title, String $_releaseDate, String $_posterPath, String $_pathName, String $_type)
    {
        $this->title = $_title;
        $this->id = $_id;
        $this->releaseDate = $_releaseDate;
        $this->posterPath = $_posterPath;
        $this->pathName = $_pathName;
        $this->type = $_type;
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

}