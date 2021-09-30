<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @ApiResource(security="is_granted('ROLE_ADMIN')")
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="company", cascade={"persist"}, orphanRemoval=true)
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity=CompanyProcess::class, mappedBy="Company")
     */
    private $companyProcesses;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->companyProcesses = new ArrayCollection();
    }

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
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setCompany($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
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
