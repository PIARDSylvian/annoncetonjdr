<?php

namespace App\Entity;

class Search 
{
    private $lat;

    private $lng;

    private $distance;

    public function getLat() 
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;
        return $this;
    }

    public function getLng() 
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;
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
}
