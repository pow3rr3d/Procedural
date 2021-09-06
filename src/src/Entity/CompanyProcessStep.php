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
    private $CompanyProcess;

    /**
     * @ORM\ManyToOne(targetEntity=Step::class)
     */
    private $Step;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $ValidatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ValidatedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyProcess(): ?CompanyProcess
    {
        return $this->CompanyProcess;
    }

    public function setCompanyProcess(?CompanyProcess $CompanyProcess): self
    {
        $this->CompanyProcess = $CompanyProcess;

        return $this;
    }

    public function getStep(): ?Step
    {
        return $this->Step;
    }

    public function setStep(?Step $Step): self
    {
        $this->Step = $Step;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeImmutable
    {
        return $this->ValidatedAt;
    }

    public function setValidatedAt(\DateTimeImmutable $ValidatedAt): self
    {
        $this->ValidatedAt = $ValidatedAt;

        return $this;
    }

    public function getValidatedBy(): ?User
    {
        return $this->ValidatedBy;
    }

    public function setValidatedBy(?User $ValidatedBy): self
    {
        $this->ValidatedBy = $ValidatedBy;

        return $this;
    }
}
