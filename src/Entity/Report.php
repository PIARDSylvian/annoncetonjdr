<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $owner;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $reason;

    /**
     * @ORM\Column(type="boolean")
     */
    private $solved = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commentary", inversedBy="reports")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $commentary;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Association", inversedBy="reports")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $association;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="reports")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Party", inversedBy="reports")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $party;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reports")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSolved(): ?bool
    {
        return $this->solved;
    }

    public function setSolved(bool $solved): self
    {
        $this->solved = $solved;

        return $this;
    }

    public function getCommentary(): ?commentary
    {
        return $this->commentary;
    }

    public function setCommentary(?commentary $commentary): self
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getParty(): ?Party
    {
        return $this->party;
    }

    public function setParty(?Party $party): self
    {
        $this->party = $party;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReported()
    {
        if($this->user !== null) {
            return $this->getUser();
        }

        if($this->party !== null) {
            return $this->party;
        }

        if($this->event !== null) {
            return $this->event;
        }

        if($this->association !== null) {
            return $this->association;
        }

        if($this->commentary !== null) {
            return $this->commentary;
        }
    }

    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->getReported()->__toString();
    }
}
