<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("card")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups("card")
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("card")
     */
    private $imageUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
