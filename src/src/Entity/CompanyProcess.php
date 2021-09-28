<?php

namespace App\Entity;

use App\Repository\CompanyProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CompanyProcessRepository::class)
 * @ApiResource()
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
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity=Process::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $process;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=CompanyProcessStep::class, mappedBy="companyProcess")
     */
    private $companyProcessSteps;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFinished;

    /**
     * @ORM\ManyToOne(targetEntity=State::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSoftDeleted;


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
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
        return $this->isFinished;
    }

    public function setIsFinished(?bool $isFinished): self
    {
        $this->isFinished = $isFinished;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getIsSoftDeleted()
    {
        return $this->isSoftDeleted;
    }

    /**
     * @param mixed $isSoftDeleted
     */
    public function setIsSoftDeleted($isSoftDeleted): void
    {
        $this->isSoftDeleted = $isSoftDeleted;
    }

}
