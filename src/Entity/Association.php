<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssociationRepository")
 */
class Association
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("card")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="association")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Location", inversedBy="association", cascade={"persist"})
     * @Assert\Valid
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("card")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("card")
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pendding = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentary", mappedBy="association", cascade={"remove"})
     */
    private $commentaries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Report", mappedBy="association", cascade={"remove"})
     */
    private $reports;

    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?user
    {
        return $this->owner;
    }

    public function setOwner(user $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAddress(): ?Location
    {
        return $this->address;
    }

    public function setAddress(Location $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getPendding(): ?bool
    {
        return $this->pendding;
    }

    public function setPendding(bool $pendding): self
    {
        $this->pendding = $pendding;

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
            $commentary->setAssociation($this);
        }

        return $this;
    }

    public function removeCommentary(commentary $commentary): self
    {
        if ($this->commentaries->contains($commentary)) {
            $this->commentaries->removeElement($commentary);
            // set the owning side to null (unless already changed)
            if ($commentary->getAssociation() === $this) {
                $commentary->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if(is_null($this->getAddress())) {
            $context->buildViolation('Cette valeur n\'est pas valide.')
                ->atPath('address.address')
                ->addViolation();
        }
    }

    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setAssociation($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getAssociation() === $this) {
                $report->setAssociation(null);
            }
        }

        return $this;
    }
}
