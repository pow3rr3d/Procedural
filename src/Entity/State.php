<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;



/**
 * @UniqueEntity("isFinalState")
 * @ORM\Entity(repositoryClass=StateRepository::class)
 * @ApiResource(security="is_granted('ROLE_ADMIN')")
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
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFinalState;

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
     * @return mixed
     */
    public function getIsFinalState()
    {
        return $this->isFinalState;
    }

    /**
     * @param mixed $isFinalState
     */
    public function setIsFinalState($isFinalState): void
    {
        $this->isFinalState = $isFinalState;
    }
}
