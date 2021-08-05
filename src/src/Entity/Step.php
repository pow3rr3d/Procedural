<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StepRepository::class)
 */
class Step
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
    private $Title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Helper;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsRequired;

    /**
     * @ORM\ManyToOne(targetEntity=Process::class, inversedBy="Steps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $process;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getHelper(): ?string
    {
        return $this->Helper;
    }

    public function setHelper(string $Helper): self
    {
        $this->Helper = $Helper;

        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->IsRequired;
    }

    public function setIsRequired(bool $IsRequired): self
    {
        $this->IsRequired = $IsRequired;

        return $this;
    }

    public function getProcess(): ?Process
    {
        return $this->process;
    }

    public function setProcess(?Process $process): self
    {
        $this->process = $process;

        return $this;
    }
}
