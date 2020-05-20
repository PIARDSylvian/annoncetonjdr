<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 */
class Note
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ambiance;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     */
    private $notePlayers;

    public function __construct()
    {
        $this->notePlayers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmbiance(): ?int
    {
        return $this->ambiance;
    }

    public function setAmbiance(?int $ambiance): self
    {
        $this->ambiance = $ambiance;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getNotePlayers(): Collection
    {
        return $this->notePlayers;
    }

    public function addNotePlayer(User $notePlayer): self
    {
        if (!$this->notePlayers->contains($notePlayer)) {
            $this->notePlayers[] = $notePlayer;
        }

        return $this;
    }

    public function removeNotePlayer(User $notePlayer): self
    {
        if ($this->notePlayers->contains($notePlayer)) {
            $this->notePlayers->removeElement($notePlayer);
        }

        return $this;
    }
}
