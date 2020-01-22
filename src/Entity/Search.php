<?php

namespace App\Entity;

class Search 
{
    private $search_lat;

    private $search_lng;

    private $page = 0;

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

    public function getPage() 
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }
}
