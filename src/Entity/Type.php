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

    #[ORM\OneToMany(mappedBy: 'id_type', targetEntity: History::class)]
    private Collection $histories;

    public function __construct()
    {
        $this->trashes = new ArrayCollection();
        $this->histories = new ArrayCollection();
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

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories->add($history);
            $history->setIdType($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getIdType() === $this) {
                $history->setIdType(null);
            }
        }

        return $this;
    }
}
