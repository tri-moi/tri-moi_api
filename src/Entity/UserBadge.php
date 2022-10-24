<?php

namespace App\Entity;

use App\Repository\UserBadgeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBadgeRepository::class)]
class UserBadge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userBadges')]
    private ?user $id_user = null;

    #[ORM\Column(length: 255)]
    private ?string $badge = null;

    #[ORM\Column(length: 255)]
    private ?string $level = null;

    #[ORM\Column]
    private ?int $nmbre_scan = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?user
    {
        return $this->id_user;
    }

    public function setIdUser(?user $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getNmbreScan(): ?int
    {
        return $this->nmbre_scan;
    }

    public function setNmbreScan(int $nmbre_scan): self
    {
        $this->nmbre_scan = $nmbre_scan;

        return $this;
    }
}
