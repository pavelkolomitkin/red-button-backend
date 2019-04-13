<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComplaintRepository")
 * @ORM\Table(name="complaint")
 */
class Complaint
{
    use GeoLocationTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $message;

    /**
     * @var ClientUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientUser", inversedBy="complaints")
     * @ORM\JoinColumn(name="client_id", nullable=false)
     */
    private $client;

    /**
     * @var ServiceType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ServiceType", inversedBy="complaints")
     * @ORM\JoinColumn(name="service_type_id", nullable=true)
     */
    private $serviceType;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ComplaintTag", inversedBy="complaints")
     * @ORM\JoinTable(name="complain_tag",
     *  joinColumns={@ORM\JoinColumn(name="complaint_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *     )
     */
    private $tags;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintConfirmation", mappedBy="complaint", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $complaintConfirmations;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="complaints")
     * @ORM\JoinColumn(name="region_id", nullable=false)
     */
    private $region;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->complaintConfirmations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Complaint
     */
    public function setClient(ClientUser $client): Complaint
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return ServiceType
     */
    public function getServiceType(): ServiceType
    {
        return $this->serviceType;
    }

    /**
     * @param ServiceType $serviceType
     * @return Complaint
     */
    public function setServiceType(ServiceType $serviceType = null): self
    {
        $this->serviceType = $serviceType;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(ComplaintTag $tag): self
    {
        if (!$this->tags->contains($tag))
        {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(ComplaintTag $tag): self
    {
        if ($this->tags->contains($tag))
        {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaintConfirmations()
    {
        return $this->complaintConfirmations;
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
     * @return Complaint
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;
        return $this;
    }
}
