<?php

namespace App\Entity;

use App\Repository\CompanyProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyProcessRepository::class)
 */
class CompanyProcess
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="companyProcesses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Company;

    /**
     * @ORM\ManyToOne(targetEntity=Process::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Process;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $UpdatedAt;

    /**
     * @ORM\OneToMany(targetEntity=CompanyProcessStep::class, mappedBy="CompanyProcess")
     */
    private $companyProcessSteps;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsFinished;

    /**
     * @ORM\ManyToOne(targetEntity=State::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $State;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsSoftDeleted;


    public function __construct()
    {
        $this->companyProcessSteps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->Company;
    }

    public function setCompany(?Company $Company): self
    {
        $this->Company = $Company;

        return $this;
    }

    public function getProcess(): ?Process
    {
        return $this->Process;
    }

    public function setProcess(?Process $Process): self
    {
        $this->Process = $Process;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    /**
     * @return Collection|CompanyProcessStep[]
     */
    public function getCompanyProcessSteps(): Collection
    {
        return $this->companyProcessSteps;
    }

    public function addCompanyProcessStep(CompanyProcessStep $companyProcessStep): self
    {
        if (!$this->companyProcessSteps->contains($companyProcessStep)) {
            $this->companyProcessSteps[] = $companyProcessStep;
            $companyProcessStep->setCompanyProcess($this);
        }

        return $this;
    }

    public function removeCompanyProcessStep(CompanyProcessStep $companyProcessStep): self
    {
        if ($this->companyProcessSteps->removeElement($companyProcessStep)) {
            // set the owning side to null (unless already changed)
            if ($companyProcessStep->getCompanyProcess() === $this) {
                $companyProcessStep->setCompanyProcess(null);
            }
        }

        return $this;
    }

    public function getIsFinished(): ?bool
    {
        return $this->IsFinished;
    }

    public function setIsFinished(?bool $IsFinished): self
    {
        $this->IsFinished = $IsFinished;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->State;
    }

    /**
     * @param mixed $State
     */
    public function setState($State): void
    {
        $this->State = $State;
    }

    /**
     * @return mixed
     */
    public function getIsSoftDeleted()
    {
        return $this->IsSoftDeleted;
    }

    /**
     * @param mixed $IsSoftDeleted
     */
    public function setIsSoftDeleted($IsSoftDeleted): void
    {
        $this->IsSoftDeleted = $IsSoftDeleted;
    }

}
