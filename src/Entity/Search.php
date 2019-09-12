<?php

namespace App\Entity;

class Search 
{
    private $search_lat;

    private $search_lng;

    private $distance;

    private $search_address;

    private $partyName;

    private $gameName;

    private $online;

    private $period;

    private $page;

    private $nombres = null;

    public function getSearchLat() 
    {
        return $this->search_lat;
    }

    public function setSearchLat(float $search_lat): self
    {
        $this->search_lat = $search_lat;
        return $this;
    }

    public function getSearchLng() 
    {
        return $this->search_lng;
    }

    public function setSearchLng(float $search_lng): self
    {
        $this->search_lng = $search_lng;
        return $this;
    }

    public function getDistance() 
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;
        return $this;
    }

    public function getSearchAddress() 
    {
        return $this->search_address;
    }

    public function setSearchAddress(string $search_address): self
    {
        $this->search_address = $search_address;
        return $this;
    }

    public function getPartyName() 
    {
        return $this->partyName;
    }

    public function setPartyName(string $partyName): self
    {
        $this->partyName = $partyName;
        return $this;
    }

    public function getGameName() 
    {
        return $this->gameName;
    }

    public function setGameName(int $gameName): self
    {
        $this->gameName = $gameName;
        return $this;
    }

    public function getOnline() 
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;
        return $this;
    }

    public function getPeriod() 
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;
        return $this;
    }

    public function getPage() 
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getNombres() 
    {
        return $this->nombres;
    }

    public function setNombres(int $nombres): self
    {
        $this->nombres = $nombres;
        return $this;
    }
}
