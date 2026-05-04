<?php

namespace App\Entity;

use App\Repository\SignalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignalRepository::class)]
#[ORM\Table(name: '`signal`')]
class Signal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column]
    private ?float $entry = null;

    #[ORM\Column]
    private ?float $stopLoss = null;

    #[ORM\Column]
    private ?float $takeProfit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $result = 'pending';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $graphic = null;

    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
        $this->result = 'pending';
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getEntry(): ?float
    {
        return $this->entry;
    }

    public function setEntry(float $entry): static
    {
        $this->entry = $entry;

        return $this;
    }

    public function getStopLoss(): ?float
    {
        return $this->stopLoss;
    }

    public function setStopLoss(float $stopLoss): static
    {
        $this->stopLoss = $stopLoss;

        return $this;
    }

    public function getTakeProfit(): ?float
    {
        return $this->takeProfit;
    }

    public function setTakeProfit(float $takeProfit): static
    {
        $this->takeProfit = $takeProfit;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getGraphic(): ?string
    {
        return $this->graphic;
    }

    public function setGraphic(?string $graphic): static
    {
        $this->graphic = $graphic;

        return $this;
    }
}
