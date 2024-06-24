<?php

namespace App\Entity;

use App\Repository\ClockingRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClockingRepository::class)]
class Clocking
{
    #[ORM\ManyToOne(inversedBy: 'clockings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $clockingProject = null;

    #[ORM\ManyToOne(inversedBy: 'clockings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $clockingUser = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $date = null;

    #[Assert\Positive]
    #[Assert\LessThanOrEqual(value: 10)]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $duration = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $clockingUsers;

    public function __construct()
    {
        $this->clockingUsers = new ArrayCollection();
    }

    public function getClockingProject(): ?Project
    {
        return $this->clockingProject;
    }

    public function setClockingProject(?Project $clockingProject): self
    {
        $this->clockingProject = $clockingProject;

        return $this;
    }

    public function getClockingUser(): ?User
    {
        return $this->clockingUser;
    }

    public function setClockingUser(?User $clockingUser): self
    {
        $this->clockingUser = $clockingUser;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClockingUsers(): Collection
    {
        return $this->clockingUsers;
    }

    public function addClockingUser(User $user): self
    {
        if (!$this->clockingUsers->contains($user)) {
            $this->clockingUsers[] = $user;
        }

        return $this;
    }

    public function removeClockingUser(User $user): self
    {
        $this->clockingUsers->removeElement($user);

        return $this;
    }
}
