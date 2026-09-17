<?php

namespace App\Entity;

use App\Repository\OrchidRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrchidRepository::class)]
class Orchid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, Watering>
     */
    #[ORM\OneToMany(targetEntity: Watering::class, mappedBy: 'orchid')]
    private Collection $waterings;

    public function __construct()
    {
        $this->waterings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Watering>
     */
    public function getWaterings(): Collection
    {
        return $this->waterings;
    }

    public function addWatering(Watering $watering): static
    {
        if (!$this->waterings->contains($watering)) {
            $this->waterings->add($watering);
            $watering->setOrchid($this);
        }

        return $this;
    }

    public function removeWatering(Watering $watering): static
    {
        if ($this->waterings->removeElement($watering)) {
            // set the owning side to null (unless already changed)
            if ($watering->getOrchid() === $this) {
                $watering->setOrchid(null);
            }
        }

        return $this;
    }
}
