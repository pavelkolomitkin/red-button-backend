<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssueRepository")
 * @ORM\Table(name="issue")
 */
class Issue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintConfirmation", mappedBy="issue", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $complaintConfirmations;

    /**
     * @var ClientUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientUser", inversedBy="issues")
     * @ORM\JoinColumn(name="client_id", nullable=false)
     */
    private $client;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="issues")
     * @ORM\JoinColumn(name="company_id", nullable=true)
     */
    private $company;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="issues")
     * @ORM\JoinColumn(name="region_id", nullable=false)
     */
    private $region;

    public function __construct()
    {
        $this->complaintConfirmations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaintConfirmations()
    {
        return $this->complaintConfirmations;
    }


    public function addComplaintConfirmation(ComplaintConfirmation $confirmation): self
    {
        if (!$this->complaintConfirmations->contains($confirmation))
        {
            $this->complaintConfirmations[] = $confirmation;
            $confirmation->setIssue($this);
        }

        return $this;
    }

    public function removeComplaintConfirmation(ComplaintConfirmation $confirmation): self
    {
        if ($this->complaintConfirmations->contains($confirmation))
        {
            $this->complaintConfirmations->removeElement($confirmation);
        }

        return $this;
    }

    /**
     * @return ClientUser
     */
    public function getClient(): ClientUser
    {
        return $this->client;
    }

    /**
     * @param ClientUser $client
     * @return Issue
     */
    public function setClient(ClientUser $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return Issue
     */
    public function setCompany(Company $company = null): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return Region
     */
    public function getRegion(): Region
    {
        return $this->region;
    }

    /**
     * @param Region $region
     * @return Issue
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;
        return $this;
    }
}
