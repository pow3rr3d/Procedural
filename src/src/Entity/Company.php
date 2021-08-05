<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
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
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="company")
     */
    private $Contacts;

    /**
     * @ORM\OneToMany(targetEntity=CompanyProcess::class, mappedBy="Company")
     */
    private $companyProcesses;

    public function __construct()
    {
        $this->Contacts = new ArrayCollection();
        $this->companyProcesses = new ArrayCollection();
    }

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
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->Contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->Contacts->contains($contact)) {
            $this->Contacts[] = $contact;
            $contact->setCompany($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->Contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getCompany() === $this) {
                $contact->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CompanyProcess[]
     */
    public function getCompanyProcesses(): Collection
    {
        return $this->companyProcesses;
    }

    public function addCompanyProcess(CompanyProcess $companyProcess): self
    {
        if (!$this->companyProcesses->contains($companyProcess)) {
            $this->companyProcesses[] = $companyProcess;
            $companyProcess->setCompany($this);
        }

        return $this;
    }

    public function removeCompanyProcess(CompanyProcess $companyProcess): self
    {
        if ($this->companyProcesses->removeElement($companyProcess)) {
            // set the owning side to null (unless already changed)
            if ($companyProcess->getCompany() === $this) {
                $companyProcess->setCompany(null);
            }
        }

        return $this;
    }
}
