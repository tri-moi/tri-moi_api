<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'id_type', targetEntity: Trash::class)]
    private Collection $trashes;

    public function __construct()
    {
        $this->trashes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Trash>
     */
    public function getTrashes(): Collection
    {
        return $this->trashes;
    }

    public function addTrash(Trash $trash): self
    {
        if (!$this->trashes->contains($trash)) {
            $this->trashes->add($trash);
            $trash->setIdType($this);
        }

        return $this;
    }

    public function removeTrash(Trash $trash): self
    {
        if ($this->trashes->removeElement($trash)) {
            // set the owning side to null (unless already changed)
            if ($trash->getIdType() === $this) {
                $trash->setIdType(null);
            }
        }

        return $this;
    }
}
