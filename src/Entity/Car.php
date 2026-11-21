<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int$id = null;

    #[ORM\Column(length: 255)]
    private ?string$model = null;

    #[ORM\Column]
    private ?int$year = null;

    #[ORM\Column(length: 255)]
    private ?string$engine = null;

    #[ORM\Column]
    private ?int$price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string$description = null;

    #[ORM\ManyToOne(targetEntity: Brand::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Brand$brand = null;

    public function getId(): ?int
    {
        return$this->id;
    }

    public function getModel(): ?string
    {
        return$this->model;
    }

    public function setModel(string$model): static
    {
        $this->model =$model;

        return$this;
    }

    public function getYear(): ?int
    {
        return$this->year;
    }

    public function setYear(int$year): static
    {
        $this->year =$year;

        return$this;
    }

    public function getEngine(): ?string
    {
        return$this->engine;
    }

    public function setEngine(string$engine): static
    {
        $this->engine =$engine;

        return$this;
    }

    public function getPrice(): ?int
    {
        return$this->price;
    }

    public function setPrice(int$price): static
    {
        $this->price =$price;

        return$this;
    }

    public function getDescription(): ?string
    {
        return$this->description;
    }

    public function setDescription(?string$description): static
    {
        $this->description =$description;

        return$this;
    } 
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }
    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }
}
