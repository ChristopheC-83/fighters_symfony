<?php

namespace App\Entity;

use App\Repository\SidesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SidesRepository::class)]
class Sides
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $side = null;

    /**
     * @var Collection<int, Fighters>
     */
    #[ORM\OneToMany(targetEntity: Fighters::class, mappedBy: 'side')]
    private Collection $fighters;

    public function __construct()
    {
        $this->fighters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSide(): ?string
    {
        return $this->side;
    }

    public function setSide(string $side): static
    {
        $this->side = $side;

        return $this;
    }

    /**
     * @return Collection<int, Fighters>
     */
    public function getFighters(): Collection
    {
        return $this->fighters;
    }

    public function addFighter(Fighters $fighter): static
    {
        if (!$this->fighters->contains($fighter)) {
            $this->fighters->add($fighter);
            $fighter->setSide($this);
        }

        return $this;
    }

    public function removeFighter(Fighters $fighter): static
    {
        if ($this->fighters->removeElement($fighter)) {
            // set the owning side to null (unless already changed)
            if ($fighter->getSide() === $this) {
                $fighter->setSide(null);
            }
        }

        return $this;
    }
}
