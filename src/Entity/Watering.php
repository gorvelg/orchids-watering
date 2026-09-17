<?php

namespace App\Entity;

use App\Enum\WateringType;
use App\Repository\WateringRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WateringRepository::class)]
class Watering
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cycleStep = null;

    #[ORM\ManyToOne(inversedBy: 'waterings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orchid $orchid = null;

    #[ORM\Column(enumType: WateringType::class)]
    private WateringType $type;

    #[ORM\Column]
    private ?\DateTimeImmutable $wateredAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCycleStep(): ?int
    {
        return $this->cycleStep;
    }

    public function setCycleStep(int $cycleStep): static
    {
        $this->cycleStep = $cycleStep;

        return $this;
    }

    public function getOrchid(): ?Orchid
    {
        return $this->orchid;
    }

    public function setOrchid(?Orchid $orchid): static
    {
        $this->orchid = $orchid;

        return $this;
    }

    public function getType(): WateringType
    {
        return $this->type;
    }

    public function setType(WateringType $type): void
    {
        $this->type = $type;
    }

    public function getWateredAt(): ?\DateTimeImmutable
    {
        return $this->wateredAt;
    }

    public function setWateredAt(\DateTimeImmutable $wateredAt): static
    {
        $this->wateredAt = $wateredAt;

        return $this;
    }
}
