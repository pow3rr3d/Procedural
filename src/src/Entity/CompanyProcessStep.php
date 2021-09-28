<?php

namespace App\Entity;

use App\Repository\CompanyProcessStepRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @ORM\Entity(repositoryClass=CompanyProcessStepRepository::class)
 * @ApiResource()
 */
class CompanyProcessStep
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompanyProcess::class, inversedBy="companyProcessSteps")
     */
    private $companyProcess;

    /**
     * @ORM\ManyToOne(targetEntity=Step::class)
     */
    private $step;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $validatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $validatedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyProcess(): ?CompanyProcess
    {
        return $this->companyProcess;
    }

    public function setCompanyProcess(?CompanyProcess $companyProcess): self
    {
        $this->companyProcess = $companyProcess;

        return $this;
    }

    public function getStep(): ?Step
    {
        return $this->step;
    }

    public function setStep(?Step $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(\DateTimeImmutable $validatedAt): self
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    public function getValidatedBy(): ?User
    {
        return $this->validatedBy;
    }

    public function setValidatedBy(?User $validatedBy): self
    {
        $this->validatedBy = $validatedBy;

        return $this;
    }
}
