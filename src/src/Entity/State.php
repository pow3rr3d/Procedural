<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;



/**
 * @UniqueEntity("IsFinalState")
 * @ORM\Entity(repositoryClass=StateRepository::class)
 * @ApiResource()
 */
class State
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsFinalState;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsFinalState()
    {
        return $this->IsFinalState;
    }

    /**
     * @param mixed $IsFinalState
     */
    public function setIsFinalState($IsFinalState): void
    {
        $this->IsFinalState = $IsFinalState;
    }
}
