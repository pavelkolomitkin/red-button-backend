<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 * @ORM\Table(name="region")
 *
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=40, nullable=true)
     */
    private $code;

    /**
     * @var FederalDistrict
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\FederalDistrict", inversedBy="regions")
     * @ORM\JoinColumn(name="federal_district_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $federalDistrict;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\AdministrativeUnit", mappedBy="region", cascade={"persist", "remove"})
     */
    private $administrativeUnits;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Complaint", mappedBy="region")
     */
    private $complaints;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="region")
     */
    private $issues;

    public function __construct()
    {
        $this->complaints = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->administrativeUnits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Region
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Region
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }


    /**
     * @return FederalDistrict
     */
    public function getFederalDistrict(): FederalDistrict
    {
        return $this->federalDistrict;
    }

    /**
     * @param FederalDistrict $federalDistrict
     * @return Region
     */
    public function setFederalDistrict(FederalDistrict $federalDistrict): self
    {
        $this->federalDistrict = $federalDistrict;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdministrativeUnits()
    {
        return $this->administrativeUnits;
    }

    /**
     * @param ArrayCollection $administrativeUnits
     * @return Region
     */
    public function setAdministrativeUnits($administrativeUnits): self
    {
        $this->administrativeUnits = $administrativeUnits;
        return $this;
    }

    public function addAdministrativeUnit(AdministrativeUnit $administrativeUnit): self
    {
        if (!$this->administrativeUnits->contains($administrativeUnit))
        {
            $this->administrativeUnits[] = $administrativeUnit;
            $administrativeUnit->setRegion($this);
        }

        return $this;
    }

    public function removeAdministrativeUnit(AdministrativeUnit $administrativeUnit): self
    {
        if ($this->administrativeUnits->contains($administrativeUnit))
        {
            $this->administrativeUnits->removeElement($administrativeUnit);
        }

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getComplaints()
    {
        return $this->complaints;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
