<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartyRepository")
 */
class Party
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThan(propertyPath="maxPlayer")
     */
    private $alreadySubscribed;


    /**
     * @ORM\Column(type="integer")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "il doit y avoir au moin un participant"
     * )
     * @Assert\NotBlank()
     */
    private $maxPlayer;

    /**
     * @ORM\Column(type="datetime")
     * @ORM\JoinColumn(nullable=false)
     * @var string A "d-m-Y H:i:s" formatted value
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = "now"
     * )
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $minor;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $gameName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gameEdition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gameScenario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $openedCampaign;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlreadySubscribed(): ?int
    {
        return $this->alreadySubscribed;
    }

    public function setAlreadySubscribed(int $alreadySubscribed): self
    {
        $this->alreadySubscribed = $alreadySubscribed;

        return $this;
    }

    public function getMaxPlayer(): ?int
    {
        return $this->maxPlayer;
    }

    public function setMaxPlayer(int $maxPlayer): self
    {
        $this->maxPlayer = $maxPlayer;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getMinor(): ?bool
    {
        return $this->minor;
    }

    public function setMinor(bool $minor): self
    {
        $this->minor = $minor;

        return $this;
    }

    public function getGameName(): ?string
    {
        return $this->gameName;
    }

    public function setGameName(string $gameName): self
    {
        $this->gameName = $gameName;

        return $this;
    }

    public function getGameEdition(): ?bool
    {
        return $this->gameEdition;
    }

    public function setGameEdition(bool $gameEdition): self
    {
        $this->gameEdition = $gameEdition;

        return $this;
    }

    public function getGameScenario(): ?bool
    {
        return $this->gameScenario;
    }

    public function setGameScenario(bool $gameScenario): self
    {
        $this->gameScenario = $gameScenario;

        return $this;
    }

    public function getOpenedCampaign(): ?bool
    {
        return $this->openedCampaign;
    }

    public function setOpenedCampaign(bool $openedCampaign): self
    {
        $this->openedCampaign = $openedCampaign;

        return $this;
    }
}
