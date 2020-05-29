<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 */
class History
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSweep;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Proof", mappedBy="history", orphanRemoval=true, cascade={"persist"})
     */
    private $proof;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant", mappedBy="history", orphanRemoval=true)
     */
    private $participants;

    public function __construct()
    {
        $this->proof = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function asArray(): array {
        $data = [];
        foreach($this as $field => $value) {
            if ($value instanceof Collection) {
                $data[$field] = $value->getValues();
            } else {
                $data[$field] = $value;
            }
        }
        return $data;
    }

    public function getCreationTime(): ?DateTimeInterface
    {
        return $this->creationTime;
    }

    public function setCreationTime(?DateTimeInterface $creationTime): self
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProof(): Collection
    {
        return $this->proof;
    }

    public function addProof(Proof $proof): self
    {
        if (!$this->proof->contains($proof)) {
            $this->proof[] = $proof;
            $proof->setHistory($this);
        }

        return $this;
    }

    public function removeProof(Proof $proof): self
    {
        if ($this->proof->contains($proof)) {
            $this->proof->removeElement($proof);
            // set the owning side to null (unless already changed)
            if ($proof->getHistory() === $this) {
                $proof->setHistory(null);
            }
        }

        return $this;
    }

    public function getIsSweep(): ?bool
    {
        return $this->isSweep;
    }

    public function setIsSweep(bool $isSweep): self
    {
        $this->isSweep = $isSweep;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setHistory($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getHistory() === $this) {
                $participant->setHistory(null);
            }
        }

        return $this;
    }
}
