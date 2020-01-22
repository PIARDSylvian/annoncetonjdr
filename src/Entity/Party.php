<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @ORM\Column(type="string", length=50)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $partyName;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThan(propertyPath="maxPlayer")
     * @Assert\Range(
     *      min = 0
     * )
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
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = "+2 hours"
     * )
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     */
    private $minor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game")
     * @ORM\JoinColumn(name="game_name_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $gameName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gameEdition;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nameScenario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $scenarioEdition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $openedCampaign;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $gameDescription;

    /**
     * @ORM\Column(type="boolean")
     */
    private $online;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     */
    private $registeredPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="parties", cascade={"persist"})
     * @Assert\Valid
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentary", mappedBy="party", cascade={"remove"})
     */
    private $commentaries;

    public function __construct()
    {
        $this->registeredPlayer = new ArrayCollection();
        $this->commentaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartyName(): ?string
    {
        return $this->partyName;
    }

    public function setPartyName(string $partyName): self
    {
        $this->partyName = $partyName;

        return $this;
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

    public function getMinor(): ?bool
    {
        return $this->minor;
    }

    public function setMinor(bool $minor): self
    {
        $this->minor = $minor;

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

    public function getNameScenario(): ?string
    {
        return $this->nameScenario;
    }

    public function setNameScenario(string $nameScenario): self
    {
        $this->nameScenario = $nameScenario;

        return $this;
    }

    public function getScenarioEdition(): ?bool
    {
        return $this->scenarioEdition;
    }

    public function setScenarioEdition(bool $scenarioEdition): self
    {
        $this->scenarioEdition = $scenarioEdition;

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

    public function getGameDescription(): ?string
    {
        return $this->gameDescription;
    }

    public function setGameDescription(?string $gameDescription): self
    {
        $this->gameDescription = $gameDescription;

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

    public function getGameName(): ?Game
    {
        return $this->gameName;
    }

    public function setGameName(?Game $gameName): self
    {
        $this->gameName = $gameName;

        return $this;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if(!$this->getOnline() && is_null($this->getAddress())) {
            $context->buildViolation('Cette valeur n\'est pas valide.')
                ->atPath('address.address')
                ->addViolation();
        }
    }

    /**
     * @return Collection|User[]
     */
    public function getRegisteredPlayer(): Collection
    {
        return $this->registeredPlayer;
    }

    public function addRegisteredPlayer(User $registeredPlayer): self
    {
        if (!$this->registeredPlayer->contains($registeredPlayer)) {
            $this->registeredPlayer[] = $registeredPlayer;
        }

        return $this;
    }

    public function removeRegisteredPlayer(User $registeredPlayer): self
    {
        if ($this->registeredPlayer->contains($registeredPlayer)) {
            $this->registeredPlayer->removeElement($registeredPlayer);
        }

        return $this;
    }

    public function getAddress(): ?Location
    {
        return $this->address;
    }

    public function setAddress(?Location $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|Commentary[]
     */
    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function addCommentary(commentary $commentary): self
    {
        if (!$this->commentaries->contains($commentary)) {
            $this->commentaries[] = $commentary;
            $commentary->setParty($this);
        }

        return $this;
    }

    public function removeCommentary(commentary $commentary): self
    {
        if ($this->commentaries->contains($commentary)) {
            $this->commentaries->removeElement($commentary);
            // set the owning side to null (unless already changed)
            if ($commentary->getParty() === $this) {
                $commentary->setParty(null);
            }
        }

        return $this;
    }
}
